<?php

namespace App\Http\Controllers;

use App\Models\ChangeLog;
use Illuminate\Http\Request;

class ChangeLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ChangeLog::with('user')->latest()->paginate(20);
        return view('admin.change_logs.index', compact('logs'));
    }
}
