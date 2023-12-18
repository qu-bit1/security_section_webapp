<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use App\Models\Attachment;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Attachments/Index', [
            'attachments' => Attachment::where('user_id', auth()->user()->id)->paginate(12),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Attachments/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttachmentRequest $request): RedirectResponse
    {
        $files = $request->file('attachments');
        foreach ($files as $file) {
            $attachment = Attachment::create([
                'name' => $file->getClientOriginalName(),
                'path' => $file->store('attachments'),
                'user_id' => auth()->user()->id,
                'mime_type' => $file->getMimeType(),
            ]);
        }

        return redirect()->back()->with('success', 'Attachment uploaded.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attachment $attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttachmentRequest $request, Attachment $attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();

        return redirect()->back()->with('success', 'Attachment deleted.');
    }
}
