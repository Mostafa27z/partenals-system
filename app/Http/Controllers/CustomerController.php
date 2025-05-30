<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;

class CustomerController extends Controller
{
    public function index(Request $request) 
    {
        $query = Customer::query();

       if ($request->filled('name')) {
        $query->where('full_name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('phone_number')) {
        $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
    }

    if ($request->filled('national_id')) {
        $query->where('national_id', 'like', '%' . $request->national_id . '%');
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

        $customers = $query->latest()->get(); // ⬅ Removed pagination

        return view('admin.customers.index', compact('customers'));
    }


    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:customers,phone_number',
            'national_id' => 'nullable|string|max:20',
        ]);

        Customer::create($request->except('_token'));


        return redirect()->route('customers.index')->with('success', 'تمت إضافة العميل بنجاح');
    }

    public function show(Customer $customer)
    {
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:customers,phone_number,' . $customer->id,
        ]);

        $customer->update($request->except('_token'));


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
}

