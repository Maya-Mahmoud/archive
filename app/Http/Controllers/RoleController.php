<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('users')->orderBy('id')->get();
        $groups = Role::permissionGroups();

        return view('roles.index', compact('roles', 'groups'));
    }

    public function create(): View
    {
        $groups = Role::permissionGroups();

        return view('roles.create', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRole($request);

        Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'permissions' => $this->cleanPermissions($request),
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Request $request, Role $role): View|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
                'description' => $role->description,
                'permissions' => $role->permissions ?? [],
            ]);
        }

        $groups = Role::permissionGroups();

        return view('roles.edit', compact('role', 'groups'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $this->validateRole($request, $role->id);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'permissions' => $this->cleanPermissions($request),
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->exists()) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete a role that is assigned to users.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted.');
    }

    private function validateRole(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:roles,name'.($ignoreId ? ",{$ignoreId}" : '')],
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
        ], [], [
            'name' => 'identifier',
            'display_name' => 'role name',
            'description' => 'description',
            'permissions' => 'permissions',
        ]);
    }

    private function cleanPermissions(Request $request): array
    {
        $selected = (array) $request->input('permissions', []);

        if (in_array('*', $selected, true)) {
            return ['*'];
        }

        return array_values(array_intersect($selected, Role::allPermissionKeys()));
    }
}
