<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Attachment;
use App\Models\Report;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $reports = Report::query()
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
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'venue' => $request->venue,
            'reporter' => $request->reporter,
            'user_id' => auth()->user()->id,
        ]);

        $report->attachments()->attach($request->attachments);
        $tagIds = [];
        foreach ($request->tags as $tag) {
            $tag = Tag::firstOrCreate(['title' => $tag]);
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
        return Inertia::render('Reports/Show', [
            'report' => $report->load('users', 'comments', "attachments"),
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
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'venue' => $request->venue,
            'reporter' => $request->reporter,
        ]);

        $report->attachments()->sync($request->attachments);

        $tagIds = [];
        foreach ($request->tags as $tag) {
            $tag = Tag::firstOrCreate(['title' => $tag]);
            if ($tag) {
                $tagIds[] = $tag->id;
            }
        };
        $report->tags()->sync($tagIds);

        return redirect()->route('reports.show', $report->id)->with('success', 'Report updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Report deleted.');
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
                'title' => 'title',
                'description' => 'description',
                'status' => 'status',
                'open' => 'status',
                'resolved' => 'status',
                'in_progress' => 'status',
                'closed' => 'status',
                'tags' => 'tags',
                'venue' => 'venue',
                'reporter' => 'reporter',
                'full' => ['title', 'description', 'venue', 'reporter']
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
                    $query->orWhere($field, $value);
                } else {
                    $query->orWhere($field, 'LIKE', "%{$value}%");
                }
        }
    }
}
