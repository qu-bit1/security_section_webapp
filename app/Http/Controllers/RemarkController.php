<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRemarkRequest;
use App\Http\Requests\UpdateRemarkRequest;
use App\Models\Remark;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class RemarkController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Remark::class, 'remark');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // it is not implemented
        return response()->json(['error' => 'Not implemented'], 501);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        // it is not implemented
        return response()->json(['error' => 'Not implemented'], 501);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRemarkRequest $request, Report $report): RedirectResponse
    {
        $remark = new Remark([
            'body' => $request->body,
            'user_id' => auth()->user()->id,
            'report_id' => $report->id
        ]);
        $remark->save();

        return redirect()->route('reports.show', $report->id)->with('success', 'Remark created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Remark $remark): JsonResponse
    {
        // it is not implemented
        return response()->json(['error' => 'Not implemented'], 501);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Remark $remark): JsonResponse
    {
        // it is not implemented
        return response()->json(['error' => 'Not implemented'], 501);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRemarkRequest $request, Remark $remark): RedirectResponse
    {
        $remark->update([
            'body' => $request->body
        ]);

        return redirect()->back()->with('success', 'Remark updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Remark $remark): RedirectResponse
    {
        $remark->delete();

        return redirect()->back()->with('success', 'Remark deleted.');
    }
}
