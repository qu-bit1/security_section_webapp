<?php

namespace App\Http\Controllers;

use App\Enums\MatchModeEnum;
use App\Enums\PermissionsEnum;
use App\Enums\StatusEnum;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Attachment;
use App\Models\Remark;
use App\Models\Report;
use App\Models\Tag;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
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

        $reports = $this->buildQuery($query, $params)
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

    public function buildQuery($query, $params)
    {
        // Apply Sorting Logic
        $query = $this->applySorting($query, $params['multiSortMeta'] ?? []);

        // Apply Filtering Logic
        return $this->applyFilters($query, $params['filters'] ?? []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request): RedirectResponse
    {
        $report = Report::create([
            'serial_number' => $request->serial_number,
            'shift' => $request->shift,
            'description' => $request->description,
            'status' => $request->status,
            'venue' => $request->venue,
            'reporter' => $request->reporter,
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
            'attachments' => Attachment::where('user_id', auth()->user()->id)->get(),
            'tags' => Tag::query()
                ->withCount("reports")
                ->orderBy("reports_count", "desc")
                ->limit(10)
                ->get(),
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
            $remarks = $this->buildQuery($query, $params)
                ->paginate(perPage: $params["rows"]??25, page: ($params["page"]??0)+1)
                ->withQueryString();
        } elseif (auth()->user()->can(PermissionsEnum::ACCESS_OWN_REMARKS->value)) {
            $remarks = $this->buildQuery($query, $params)
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
            'attachments' => Attachment::where('user_id', auth()->user()->id)->get(),
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
            'shift' => $request->shift,
            'description' => $request->description,
            'status' => $request->status,
            'venue' => $request->venue,
            'reporter' => $request->reporter,
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

    private function applySorting($query, $sortMeta)
    {
        foreach ($sortMeta as $sort) {
            $order = $sort['order'] === 1 ? 'asc' : 'desc';
            $field = $sort['field'];
            if (in_array($field, ['attachments', 'tags'])) {
                $query->withCount($field)->orderBy($field . '_count', $order);
            } else {
                $query->orderBy($field, $order);
            }
        }
        return $query;
    }

    private function applyFilters($query, $filters)
    {
        if (!empty($filters)) {
            foreach ($filters as $field => $filter) {
                $value = $filter['value'];
                $matchMode = $filter['matchMode'];

                if (empty($value)) {
                    continue;
                }

                if ($field === 'global') {
                    $this->applySearchFilter($query, $value);
                    continue;
                }

                // Applying different match modes
                switch ($matchMode) {
                    case MatchModeEnum::STARTS_WITH->value:
                        $query->where($field, 'like', "$value%");
                        break;
                    case MatchModeEnum::CONTAINS->value:
                        $query->where($field, 'like', "%$value%");
                        break;
                    case MatchModeEnum::NOT_CONTAINS->value:
                        $query->where($field, 'not like', "%$value%");
                        break;
                    case MatchModeEnum::ENDS_WITH->value:
                        $query->where($field, 'like', "%$value");
                        break;
                    case MatchModeEnum::EQUALS->value:
                        $query->where($field, '=', $value);
                        break;
                    case MatchModeEnum::NOT_EQUALS->value:
                        $query->where($field, '!=', $value);
                        break;
                    case MatchModeEnum::IN->value:
                        if ($field === "tags") {
                            $query->whereHas($field, function ($q) use ($value) {
                                $q->whereIn('title', $value);
                            });
                            break;
                        }

                        $values = collect($value)->pluck('value')->toArray();
                        $query->whereIn($field, $values);
                        break;
                    case MatchModeEnum::LESS_THAN->value:
                        $query->where($field, '<', $value);
                        break;
                    case MatchModeEnum::LESS_THAN_OR_EQUAL_TO->value:
                        $query->where($field, '<=', $value);
                        break;
                    case MatchModeEnum::GREATER_THAN->value:
                        $query->where($field, '>', $value);
                        break;
                    case MatchModeEnum::GREATER_THAN_OR_EQUAL_TO->value:
                        $query->where($field, '>=', $value);
                        break;
                    case MatchModeEnum::BETWEEN->value:
                        $query->whereBetween($field, [$value]);
                        break;
                    case MatchModeEnum::DATE_IS->value:
                        $query->whereDate($field, $value);
                        break;
                    case MatchModeEnum::DATE_IS_NOT->value:
                        $query->whereDate($field, '!=', $value);
                        break;
                    case MatchModeEnum::DATE_BEFORE->value:
                        $query->whereDate($field, '<', $value);
                        break;
                    case MatchModeEnum::DATE_AFTER->value:
                        $query->whereDate($field, '>', $value);
                        break;
                }
            }
        }
        return $query;
    }

    /*
     * @Deprecated: use applyFilters instead
     */
    private function applySearchFilter($query, $search): void
    {
        $tagPattern = '/\[(.*?)]/';
        preg_match_all($tagPattern, $search, $tagMatches);

        $tags = $tagMatches[1];

        $searchWithoutTags = preg_replace($tagPattern, '', $search);

        $pattern = '/(?<key>\w+)\s*:\s*(?<value>.*?)(?=\w+\s*:|$)/';
        preg_match_all($pattern, $searchWithoutTags, $matches, PREG_SET_ORDER);
        $searchCriteria = [];

        foreach ($matches as $match) {
            $searchCriteria[$match['key']] = trim($match['value']);
        }
        if(empty($searchCriteria)) {
            $searchCriteria['full'] = trim($searchWithoutTags);
        }
        if($tags){
            $searchCriteria['tags'] = $tags;
        }

        $query->where(function ($query) use ($searchCriteria) {
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
                'reporter' => 'reporter',
                'full' => ['description', 'venue', 'reporter']
            ];

            foreach ($searchCriteria as $key => $value) {
                if (array_key_exists($key, $fieldMappings)) {
                    $field = $fieldMappings[$key];
                    $this->applyFilter($query, $field, $key, $value);
                }
            }
        });
    }

    private function applyFilter($query, $field, $key, $value): void
    {
        switch (true) {
            case in_array($key, ['open', 'resolved', 'in_progress', 'closed']):
                if ($value == 'no') {
                    $query->where($field, '<>', $key);
                } else {
                    $query->where($field, $key);
                }
                break;
            case $key == 'tags':
                $query->whereHas($field, function ($query) use ($key, $value) {
                    $query->where('title', $value);
                });
                break;
            case $key == 'full':
                if (is_array($field) || is_object($field)) {
                    $query->where(function ($query) use ($key, $value, $field) {
                        foreach ($field as $k=>$v) {
                            $this->applyFilter($query, $v, $v, $value);
                        }
                    });
                }
                break;
            default:
                if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
                    $value = trim($value, '"');
                    if ($key == "status"){
                        $value = StatusEnum::getValueFromLabel($value)->value;
                    }
                    $query->orWhere($field, $value);
                } else {
                    if ($key == "status"){
                        $value = StatusEnum::getValueFromLabel($value)->value;
                    }
                    $query->orWhere($field, 'LIKE', "%{$value}%");
                }
        }
    }
}
