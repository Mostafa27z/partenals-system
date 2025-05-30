<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->get(); // eager load roles
        $roles = Role::all();

        return view('admin.permissions', compact('permissions', 'roles'));
    }

    public function update(Request $request)
    {
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            $roleIds = $request->input('permission_' . $permission->id, []);
            $permission->roles()->sync($roleIds); // تحديث الصلاحيات
        }

        return redirect()->route('permissions.index')->with('success', 'تم تحديث الصلاحيات بنجاح.');
    }
}

