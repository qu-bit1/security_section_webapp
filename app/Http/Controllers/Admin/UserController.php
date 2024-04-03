<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(): Response
    {
        $user = auth()->user();
        if($user->cannot(PermissionsEnum::ACCESS_USERS->value)){
            abort(403);
        }

        $params = json_decode(request()->input('lazyEvent'), true);
        $query = User::query()
            ->with('roles')->whereDoesntHave('roles', function ($query) {
                $query->where('name', RolesEnum::SUPER_ADMIN);
            });

        $fieldMappings = [
            'name' => 'name',
            'email' => 'email',
            'roles' => 'roles',
            'full' => ['name', 'email']
        ];

        $users = buildQuery($query, $params, $fieldMappings)
            ->paginate(perPage: $params["rows"]??25, page: ($params["page"]??0)+1)
            ->withQueryString();
        return inertia('Users/Index', [
            'users' => $users,
            'roles' => Role::query()
                ->withCount("users")
                ->orderBy("users_count", "desc")
                ->whereNotIn('name', ['super-admin'])->get(),
            'filters' => request()->all(),
        ]);
    }

    public function assignRoles(UpdateUserRequest $request): RedirectResponse
    {
        if (auth()->user()->cannot(PermissionsEnum::ASSIGN_ROLES->value)) {
            abort(403);
        }

        $user = User::find($request->id);
        $user->syncRoles($request->roles);
        return redirect()->back()->with('success', 'Roles assigned successfully');
    }
}
