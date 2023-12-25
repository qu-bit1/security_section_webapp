<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Attachment;
use App\Models\Report;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
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
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
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
            ->when(request('sort') == 'oldest', function ($query) {
                $query->oldest();
            }, function ($query) {
                $query->latest();
            })
            ->with(['attachments', 'tags'])
            ->paginate(12)
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
}
