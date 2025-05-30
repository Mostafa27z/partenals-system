<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    // عرض صفحة لوحة التحكم مع حالة الصلاحيات
    public function index()
    {
        $permissions = DB::table('permissions')->get();
        return view('admin.dashboard', compact('permissions'));
    }

    // تحديث حالة الصلاحيات
    public function update(Request $request)
    {
        // نفترض إن الطلب بيأتي على شكل array للـ permissions المفعّلة
        $activePermissions = $request->input('permissions', []);
        
        // تحديث كل صلاحية حسب القيمة المرسلة
        $allPermissions = DB::table('permissions')->get();
        
        foreach ($allPermissions as $permission) {
            DB::table('permissions')
                ->where('id', $permission->id)
                ->update(['is_active' => in_array($permission->name, $activePermissions)]);
        }
        
        return redirect()->back()->with('success', 'تم تحديث الصلاحيات بنجاح');
    }
}
