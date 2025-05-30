<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::first();
        return view('admin.company', compact('company'));
    }
    public function edit()
    {
        $company = Company::first();
        return view('admin.company.edit', compact('company'));
    }
public function update(Request $request, $id)
{
    $company = Company::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|max:2048',
        // باقي الحقول حسب الحاجة
    ]);

    // رفع الشعار لو موجود
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('logos', 'public');
        $validated['logo'] = $logoPath;
    }

    $company->update($validated);

    return redirect()->route('company.edit', $company->id)->with('success', 'تم تحديث بيانات الشركة بنجاح');
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'nullable|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image',
            'email_activation' => 'nullable|string',
            'active_username' => 'nullable|string',
            'active_password' => 'nullable|string',
            'active_port' => 'nullable|integer',
            'suspension_penalty_days' => 'nullable|integer',
            'allowed_suspension_days' => 'nullable|integer',
            'email_problem' => 'nullable|string',
            'problem_username' => 'nullable|string',
            'problem_password' => 'nullable|string',
            'problem_port' => 'nullable|integer',
            'smtp_configuration' => 'nullable|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'bcc2' => 'nullable|string',
            'portal_username' => 'nullable|string',
            'portal_password' => 'nullable|string',
        ]);

        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('logos', 'public');
        }

        Company::updateOrCreate(['id' => 1], $data);

        return redirect()->route('company.edit')->with('success', 'تم حفظ بيانات الشركة بنجاح');
    }
}
