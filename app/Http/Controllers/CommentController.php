<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(['error' => 'Not implemented'], 501);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        return response()->json(['error' => 'Not implemented'], 501);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Report $report): RedirectResponse
    {
        $comment = new Comment([
            'body' => $request->body,
            'user_id' => auth()->user()->id,
            'report_id' => $report->id
        ]);
        $comment->save();

        return redirect()->route('reports.show', $report->id)->with('success', 'Comment created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        return response()->json(['error' => 'Not implemented'], 501);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment): JsonResponse
    {
        return response()->json(['error' => 'Not implemented'], 501);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $comment->update([
            'body' => $request->body
        ]);

        return redirect()->back()->with('success', 'Comment updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted');
    }
}
