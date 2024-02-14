<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PermissionsEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Response|RedirectResponse
    {
        if (!auth()->user()->can(PermissionsEnum::ACCESS_PERMISSIONS->value)) {
            return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
        }

        return Inertia::render('Permissions/Index', [
            'permissions' => PermissionsEnum::toArray(),
        ]);
    }
}
