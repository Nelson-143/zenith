<?php
namespace app\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\Role;
use app\Models\User;

class RoleController extends Controller
{
    // Display a list of all roles
    public function index()
    {
        $roles = Role::all(); // Assuming you have a Role model
        return view('roles.index', compact('roles'));
    }

    // Show form to create a new role
    public function create()
    {
        return view('roles.create');
    }

    // Store a newly created role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    // Show the form to edit a role
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    // Update a role
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|max:255',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    // Delete a role
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    // Assign role to a user
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->sync([$request->role_id]); // Assuming many-to-many relationship
        return redirect()->route('users.index')->with('success', 'Role assigned successfully.');
    }
}
