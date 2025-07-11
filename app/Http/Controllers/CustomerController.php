<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Line;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('lines');

        if ($request->filled('name')) {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('national_id')) {
            $query->where('national_id', 'like', '%' . $request->national_id . '%');
        }

        if ($request->filled('phone_number')) {
            $query->whereHas('lines', function ($q) use ($request) {
                $q->where('phone_number', 'like', '%' . $request->phone_number . '%');
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('lines', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
        $customers = $query->paginate(10);
        // $customers = $query->latest()->get(); // Consider paginate() in production

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        $plans = Plan::all();
        return view('admin.customers.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14|unique:customers,national_id',
            'phone_number' => 'required|string|max:20|unique:lines,phone_number',
        ]);

        $customer = Customer::create([
            'full_name' => $request->full_name,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
        ]);

        // إنشاء الخط المرتبط بالعميل
        $customer->lines()->create([
            'phone_number' => $request->phone_number,
            'provider' => $request->provider,
            'status' => $request->status,
            'plan_id' => $request->plan_id,
            'line_type' => $request->line_type,
            'payment_date' => $request->payment_date,
            'added_by' => auth()->id(),
        ]);

        return redirect()->route('customers.index')->with('success', 'تمت إضافة العميل بنجاح');
    }

    public function show(Customer $customer)
    {
        $customer->load('lines');
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $customer->load('lines');
        $plans = Plan::all();
        return view('admin.customers.edit', compact('customer', 'plans'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|size:14|unique:customers,national_id,' . $customer->id,
        ]);

        $customer->update([
            'full_name' => $request->full_name,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
        ]);

        // تحديث أول خط (بافتراض خط واحد هنا - يمكنك تعديل هذا لاحقًا لدعم أكثر من خط)
        if ($customer->lines()->exists()) {
            $line = $customer->lines()->first();
            $line->update([
                'phone_number' => $request->phone_number,
                'provider' => $request->provider,
                'status' => $request->status,
                'plan_id' => $request->plan_id,
                'line_type' => $request->line_type,
                'payment_date' => $request->payment_date,
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'تم حذف العميل بنجاح');
    }

    public function export()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }
    public function search(Request $request)
{
    $term = $request->q;

    $customers = Customer::where('national_id', 'like', "%$term%")
        ->select('id', 'full_name', 'national_id')
        ->limit(20)
        ->get();

    return response()->json($customers);
}
public function searchByNationalId(Request $request)
{
    $query = $request->get('q', '');
    return Customer::where('national_id', 'like', "%$query%")
        ->select('id', 'full_name', 'national_id')
        ->limit(20)
        ->get();
}
public function trashed()
{
    $customers = Customer::onlyTrashed()->with('lines')->paginate(20);
    return view('admin.customers.trashed', compact('customers'));
}

public function restore($id)
{
    $customer = Customer::onlyTrashed()->findOrFail($id);
    $customer->restore();

    return redirect()->route('customers.trashed')->with('success', '✅ تم استرجاع العميل بنجاح');
}

public function forceDelete($id)
{
    $customer = Customer::onlyTrashed()->findOrFail($id);
    $customer->forceDelete();

    return redirect()->route('customers.trashed')->with('success', '🗑️ تم حذف العميل نهائياً');
}

}
