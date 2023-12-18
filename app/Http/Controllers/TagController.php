<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Inertia\Response;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('Tags/Index', [
            'tags' => Tag::query()
                ->when(request('search'), function ($query, $search) {
                    $query->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                })
                ->where('user_id', auth()->user()->id)
                ->paginate(12)
                ->withQueryString(),
            'filters' => request()->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Tags/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create([
            'title' => $request->title,
            "user_id" => auth()->user()->id,
            "description" => $request->description,
        ]);

        return redirect()->back()->with('success', 'Tag created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        //
    }

    public function search(): JsonResponse
    {
        return response()->json([
            "tags"=>Tag::query()
                ->when(request('search'), function ($query, $search) {
                    $query->where('title', 'LIKE', "%{$search}%");
                })
                ->limit(10)
                ->get()->map(function ($tag) {
                    return [
                        'key' => $tag->id,
                        'value' => $tag->title,
                    ];
                }),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
