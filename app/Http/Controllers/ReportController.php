<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Attachment;
use App\Models\Report;
use Inertia\Inertia;
use Inertia\Response;
use \Illuminate\Http\RedirectResponse;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Reports/Index', [
            'reports' => Report::withCount(['attachments'])->paginate(12),
        ]);
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

        return redirect()->route('reports.index')->with('success', 'Report created.');
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
            'report' => $report->load('users', 'attachments'),
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
