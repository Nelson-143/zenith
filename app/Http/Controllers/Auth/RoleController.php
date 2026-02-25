<?php
namespace app\Http\Controllers\Auth;

use app\Models\Role;
use app\Models\Permission;
use app\Models\User;
use Illuminate\Http\Request;

class RoleController 
{
    public function assignRole(Request $request, User $user)
    {
        $role = Role::findOrFail($request->role_id);
        $user->roles()->syncWithoutDetaching([$role->id]);
        return response()->json(['message' => 'Role assigned successfully.']);
    }

    public function revokeRole(Request $request, User $user)
    {
        $user->roles()->detach($request->role_id);
        return response()->json(['message' => 'Role revoked successfully.']);
    }

    public function assignPermission(Request $request, Role $role)
    {
        $permission = Permission::findOrFail($request->permission_id);
        $role->permissions()->syncWithoutDetaching([$permission->id]);
        return response()->json(['message' => 'Permission assigned successfully.']);
    }

    public function revokePermission(Request $request, Role $role)
    {
        $role->permissions()->detach($request->permission_id);
        return response()->json(['message' => 'Permission revoked successfully.']);
    }
}