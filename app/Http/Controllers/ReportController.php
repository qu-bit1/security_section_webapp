<?php

namespace App\Http\Controllers;

use App\Enums\PermissionsEnum;
use App\Enums\StatusEnum;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Attachment;
use App\Models\Report;
use App\Models\Tag;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use \Illuminate\Http\Response as HTTPResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
        $user = auth()->user();
        $reports = Report::query()
            ->when($user->cannot(PermissionsEnum::ACCESS_ALL_REPORTS->value), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when(request('search'), function ($query, $search) {
                $this->applySearchFilter($query, $search);
            })
            ->when(request('venue'), function ($query, $venue) {
                $query->where('venue', 'LIKE', "%{$venue}%");
            })
            ->when(request('reporter'), function ($query, $reporter) {
                $query->where('reporter', 'LIKE', "%{$reporter}%");
            })
            ->when(request('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(request('tags'), function ($query, $tag) {
                $query->whereHas('tags', function ($query) use ($tag) {
                    $query->where('title', $tag);
                });
            })
            ->when(request('attachment'), function ($query, $attachment) {
                $query->whereHas('attachments', function ($query) use ($attachment) {
                    $query->where('name', $attachment)
                        ->orWhere('mime_type', $attachment);
                });
            })
            ->orderBy('created_at', 'desc')
            ->with(['attachments', 'tags'])
            ->paginate(request('per_page', 25))
            ->withQueryString();
        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            'filters' => request()->all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request): RedirectResponse
    {
        $report = Report::create([
            'shift' => $request->shift,
            'description' => $request->description,
            'status' => $request->status,
            'venue' => $request->venue,
            'reporter' => $request->reporter,
            'user_id' => auth()->user()->id,
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
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): Response
    {
        $remarks = [];
        if (auth()->user()->can(PermissionsEnum::ACCESS_ALL_REMARKS->value)) {
            $remarks = $report->remarks()->with('user')->get();
        } elseif (auth()->user()->can(PermissionsEnum::ACCESS_OWN_REMARKS->value)) {
            $remarks = $report->remarks()->where('user_id', auth()->user()->id)->with('user')->get();
        }

        return Inertia::render('Reports/Show', [
            'report' => $report->load(['attachments', 'tags']),
            'remarks' => $remarks,
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

    public function approve(): Response
    {
        $this->authorize('approve', Report::class);
        $reports = Report::query()
            ->where('approved', false)
            ->with(['attachments', 'tags'])
            ->get();

        return Inertia::render('Reports/Approve', [
            'reports' => $reports,
        ]);
    }

    public function approveOne(Report $report): RedirectResponse
    {
        $this->authorize('approveOne', $report);

        $report->update(['approved' => true]);

        return redirect()->route('reports.approve')->with('success', 'Report approved.');
    }

    public function approveAll(Request $request): RedirectResponse
    {
        $this->authorize('approveAll', Report::class);

        $reportIds = $request->reports;
        Report::whereIn('id', $reportIds)->update(['approved' => true]);

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
     * Download the report in specified format.
     */
    public function generate(Report $report): HTTPResponse|BinaryFileResponse
    {
        return $this->reportService->generate($report, request('format'));
    }

    /**
     * Export the reports in specified format.
     */
    public function export(): BinaryFileResponse
    {
        return $this->reportService->export(request("format"));
    }

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
