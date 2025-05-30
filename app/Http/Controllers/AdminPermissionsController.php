<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPermissionsController extends Controller
{
    public function index()
{
    $permissions = \App\Models\Permission::with('roles')->get();
    $roles = \App\Models\Role::all();
    return view('admin.permissions', compact('permissions', 'roles'));
}

public function update(Request $request)
{
    $permissions = \App\Models\Permission::all();
    foreach ($permissions as $permission) {
        $selectedRoles = $request->input("permission_{$permission->id}", []);
        $permission->roles()->sync($selectedRoles); // sync يقوم بتحديث العلاقات
    }

    return back()->with('success', 'تم تحديث الصلاحيات بنجاح');
}

}
