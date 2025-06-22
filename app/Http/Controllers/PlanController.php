<?php
namespace App\Http\Controllers;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Exports\PlansExport;
use Maatwebsite\Excel\Facades\Excel;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = Plan::query();

        if ($request->filled('search')) {
            $plans->where('name', 'like', "%{$request->search}%")
                  ->orWhere('provider', 'like', "%{$request->search}%")
                  ->orWhere('plan_code', 'like', "%{$request->search}%")
                  ->orWhere('type', 'like', "%{$request->search}%");
        }

        $plans = $plans->paginate(10);

        return view('admin.plans.index', compact('plans'));
    }

    public function export()
    {
        return Excel::download(new PlansExport, 'plans.xlsx');
    }

    public function create()
{
    return view('admin.plans.create');
}

public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
        'provider' => 'nullable|string',
        'provider_price' => 'nullable|numeric',
        'type' => 'nullable|string',
        'identifier' => 'nullable|string',
        'penalty' => 'nullable|string',
        'plan_code' => 'nullable',
        
    ]);

    Plan::create($data);

    return redirect()->route('plans.index')->with('success', 'تم إنشاء النظام بنجاح');
}

public function edit(Plan $plan)
{
    return view('admin.plans.edit', compact('plan'));
}

public function update(Request $request, Plan $plan)
{
    $data = $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
        'provider' => 'nullable|string',
        'provider_price' => 'nullable|numeric',
        'type' => 'nullable|string',
        'identifier' => 'nullable|string',
        'penalty' => 'nullable|string',
        'plan_code' => 'nullable',
    ]);

    $plan->update($data);

    return redirect()->route('plans.index')->with('success', 'تم تعديل النظام بنجاح');
}

public function show(Plan $plan)
{
    return view('admin.plans.show', compact('plan'));
}
public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'تم حذف النظام بنجاح');
    }
}
