<?php

namespace App\Http\Controllers;

use App\Enums\PermissionsEnum;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Remark;
use App\Models\Report;
use App\Models\Tag;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use STS\ZipStream\ZipStream;

class ReportController extends Controller
{
    private ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->authorizeResource(Report::class, 'report');
        $this->reportService = $reportService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $params = json_decode(request()->input('lazyEvent'), true);

        $user = auth()->user();

        $query = Report::query()
            ->when($user->cannot(PermissionsEnum::ACCESS_ALL_REPORTS->value), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['attachments', 'tags'])
            ->withCount(['remarks', 'comments']);

        $fieldMappings = [
            'shift' => 'shift',
            'description' => 'description',
            'status' => 'status',
            'open' => 'status',
            'resolved' => 'status',
            'in_progress' => 'status',
            'closed' => 'status',
            'tags' => 'tags',
            'venue' => 'venue',
            'full' => ['description', 'venue']
        ];

        $reports = buildQuery($query, $params, $fieldMappings)
            ->paginate(perPage: $params["rows"]??25, page: ($params["page"]??0)+1)
            ->withQueryString();

        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            'tags' => Tag::query()
                ->withCount("reports")
                ->orderBy("reports_count", "desc")
                ->limit(10)
                ->get(),
            'filters' => request()->all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request): RedirectResponse
    {
        $report = Report::create([
            'serial_number' => $request->serial_number,
            'description' => $request->description,
            'venue' => $request->venue,
            'user_id' => auth()->user()->id,
            'reported_at' => Carbon::parse($request->reported_at ?? now()),
            'dealing_officer' => $request->dealing_officer,
        ]);

        $report->attachments()->attach($request->attachments);
        $tagIds = [];
        foreach ($request->tags as $tag) {
            $tag = Tag::firstOrCreate(['title' => $tag], ['user_id' => auth()->user()->id]);
            if ($tag) {
                $tagIds[] = $tag->id;
            }
        };
        $report->tags()->attach($tagIds);

        return redirect()->route('reports.index')->with('success', 'Report created.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Reports/Create', [
            'tags' => Tag::query()
                ->withCount("reports")
                ->orderBy("reports_count", "desc")
                ->limit(10)
                ->get(),
            "params" => request()->all(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): Response
    {
        $params = json_decode(request()->input('lazyEvent'), true);

        $query = Remark::query()->with('user');
        $remarks = [];
        if (auth()->user()->can(PermissionsEnum::ACCESS_ALL_REMARKS->value)) {
            $remarks = buildQuery($query, $params)
                ->paginate(perPage: $params["rows"]??25, page: ($params["page"]??0)+1)
                ->withQueryString();
        } elseif (auth()->user()->can(PermissionsEnum::ACCESS_OWN_REMARKS->value)) {
            $remarks = buildQuery($query, $params)
                ->where('user_id', auth()->user()->id)
                ->paginate(perPage: $params["rows"]??25, page: ($params["page"]??0)+1)
                ->withQueryString();
        }

        $comments = $report->comments()->with('user')->paginate(request('per_page', 25));
        return Inertia::render('Reports/Show', [
            'report' => $report->load(['attachments', 'tags']),
            'remarks' => $remarks,
            'comments' => $comments,
            'filters' => request()->all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report): Response
    {
        return Inertia::render('Reports/Edit', [
            'report' => $report->load('users', 'attachments', "tags"),
            'tags' => Tag::query()
                ->withCount("reports")
                ->orderBy("reports_count", "desc")
                ->limit(10)
                ->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report): RedirectResponse
    {
        $report->update([
            'description' => $request->description,
            'venue' => $request->venue,
            'reported_at' => Carbon::parse($request->reported_at ?? now()),
            'dealing_officer' => $request->dealing_officer,
        ]);

        $report->attachments()->sync($request->attachments);

        $tagIds = [];
        foreach ($request->tags as $tag) {
            $tag = Tag::firstOrCreate(['title' => $tag], ['user_id' => auth()->user()->id]);
            if ($tag) {
                $tagIds[] = $tag->id;
            }
        };
        $report->tags()->sync($tagIds);

        return redirect()->route('reports.show', $report->id)->with('success', 'Report updated.');
    }

    public function approveOne(Report $report): RedirectResponse
    {
        $this->authorize('approveOne', $report);

        $report->update([
            'approved' => true,
            'approved_by' => auth()->user()->id,
        ]);

        return redirect()->route('reports.approve')->with('success', 'Report approved.');
    }

    public function massApprove(Request $request): RedirectResponse
    {
        $this->authorize('massApprove', Report::class);

        $reportIds = $request->reports;
        Report::whereIn('id', $reportIds)
            ->where('approved', false)
            ->update([
                'approved' => true,
                'approved_by' => auth()->user()->id,
                'approved_at' => now(),
            ]);

        return redirect()->route('reports.index')->with('success', 'Reports approved.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Report deleted.');
    }

    /**
     * Remove the specified resources from storage.
     */
    public function massDestroy(Request $request): RedirectResponse
    {
        if (empty($request->reports)) {
            return redirect()->route('reports.index')->with('error', 'No reports selected.');
        }

        $user = auth()->user();

        if ($user->can(PermissionsEnum::DELETE_ALL_REPORTS->value)) {
            Report::destroy($request->reports);
            return redirect()->route('reports.index')->with('success', 'Reports deleted.');
        }

        if ($user->can(PermissionsEnum::DELETE_OWN_REPORTS->value)) {
            Report::query()->whereIn('id', $request->reports)->where([
                ['user_id', $user->id],
                ['approved', false]
            ])->delete();
            return redirect()->route('reports.index')->with('success', 'Reports deleted.');
        }

        return redirect()->route('reports.index')->with('error', 'You are not authorized to delete reports.');
    }

    /**
     * Export the reports in zip format
     */
    public function export(Request $request): RedirectResponse|ZipStream
    {

        if(empty($request->reports)){
            return redirect()->route('reports.index')->with('error', 'No reports selected.');
        }

        $user = auth()->user();

        if ($user->can(PermissionsEnum::ACCESS_ALL_REPORTS->value)) {
            $reports = Report::whereIn('id', $request->reports)->with(['tags', 'attachments'])->get();
            return $this->reportService->export($reports);
        }

        if ($user->can(PermissionsEnum::ACCESS_OWN_REPORTS->value)) {
            $reports = Report::whereIn('id', $request->reports)->where('user_id', $user->id)->with(['tags', 'attachments'])->get();
            return $this->reportService->export($reports);
        }

        return redirect()->route('reports.index')->with('error', 'You are not authorized to export reports.');
    }
}
