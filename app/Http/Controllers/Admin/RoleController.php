<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('Roles/Index', [
            'roles' => Role::with(["permissions"])->whereNotIn('name', ['super-admin'])->withCount("users")->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Roles/Create', [
            'permissions' => Permission::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        if (Role::where('name', $request->name)->exists()) {
            return redirect()->route('roles.create')
                ->withErrors(['name' => 'The role name already exists. Please choose a different name.']);
        }

        Role::create(['name' => $request->name])->syncPermissions($request->permissions);
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): Response
    {
        return Inertia::render('Roles/Show', [
            'role' => $role->load('permissions'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): Response
    {
        return inertia('Roles/Edit', [
            'role' => $role->load('permissions'),
            'permissions' => Permission::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        // Check if the role name already exists except for the current role being updated
        if (Role::where('name', $request->name)->where('id', '!=', $role->id)->exists()) {
            return redirect()->route('roles.edit', $role)
                ->withErrors(['name' => 'The role name already exists. Please choose a different name.']);
        }

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with('success', 'Role updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }
}
