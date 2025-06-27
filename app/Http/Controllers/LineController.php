<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Line;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\LinesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
class LineController extends Controller
{
    public function importForm()
{
    return view('admin.lines.import');
}

public function importProcess(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx'
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $count = 0;
    $errors = [];
    $validGcodes = ['010', '011', '012', '015'];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $rowNumber = $index + 1;
        $phone = trim($row[0] ?? '');
        $gcode = trim($row[1] ?? '');
        $planName = trim($row[2] ?? '');
        $fullName = trim($row[3] ?? '');
        $nationalId = trim($row[4] ?? '');

        if (!$phone) {
            $errors[] = "السطر $rowNumber: رقم الهاتف مطلوب.";
            continue;
        }

        if (!preg_match('/^\d{11}$/', $phone)) {
            $errors[] = "السطر $rowNumber: رقم الهاتف يجب أن يكون 11 رقم.";
            continue;
        }

        if (Line::where('phone_number', $phone)->exists()) {
            $errors[] = "السطر $rowNumber: رقم الهاتف $phone مستخدم بالفعل.";
            continue;
        }

        if (!$gcode || !in_array($gcode, $validGcodes)) {
            $errors[] = "السطر $rowNumber: كود GCode غير صالح ($gcode).";
            continue;
        }

        // if (!$planName) {
        //     $errors[] = "السطر $rowNumber: اسم النظام مطلوب.";
        //     continue;
        // }

        $plan = Plan::where('name', $planName)->first();
        if (!$plan) {
            $errors[] = "السطر $rowNumber: النظام '$planName' غير موجود.";
            continue;
        }

        $customerId = null;
        if ($nationalId) {
            $customer = Customer::where('national_id', $nationalId)->first();

            if (!$customer && $fullName) {
                $customer = Customer::create([
                    'full_name' => $fullName,
                    'national_id' => $nationalId,
                ]);
            }

            $customerId = $customer?->id;
        }

        Line::create([
            'phone_number' => $phone,
            'gcode' => $gcode,
            'customer_id' => $customerId,
            'plan_id' => $plan->id,
            'added_by' => Auth::id(),
        ]);

        $count++;
    }

    // ✅ إذا وجدت أخطاء، أنشئ ملف نصي للتحميل فورًا
    if (count($errors)) {
        $content = implode("\n", $errors);
        $filename = 'import_errors_' . now()->format('Ymd_His') . '.txt';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'text/plain',
        ]);
    }

    return redirect()->route('lines.all')->with('success', "تم استيراد $count خط بنجاح.");
}
    public function export()
    {
        return Excel::download(new LinesExport, 'lines.xlsx');
    }

    public function all(Request $request)
    {
        $query = Line::with(['customer', 'plan']);

        if ($request->filled('phone')) {
            $query->where('phone_number', 'like', '%' . $request->phone . '%');
        }
        if ($request->filled('distributor')) {
            $query->where('distributor', 'like', '%' . $request->distributor . '%');
        }

        if ($request->filled('provider')) {
            $query->where('provider', 'like', '%' . $request->provider . '%');
        }

        if ($request->filled('customer')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->customer . '%');
            });
        }

        $lines = $query->latest()->paginate(20);

        return view('admin.lines.all', compact('lines'));
    }

    public function index(Customer $customer)
    {
        $lines = $customer->lines()->with('plan')->get();
        return view('admin.lines.index', compact('customer', 'lines'));
    }

    public function create(Customer $customer)
    {
        $plans = Plan::all();
        return view('admin.lines.create', compact('customer', 'plans'));
    }

    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate($this->rules());

        $full_number =$validated['phone_number'];
        $exists = Line::whereRaw("CONCAT(gcode, phone_number) = ?", [$full_number])->exists();

        if ($exists) {
            return back()->withErrors(['phone_number' => 'رقم الهاتف هذا مستخدم بالفعل'])->withInput();
        }

        // $expectedProviders = $this->expectedProviders();
        // if ($expectedProviders[$validated['gcode']] !== $validated['provider']) {
        //     return back()->withErrors(['provider' => 'مزود الخدمة لا يتطابق مع كود الخط المحدد'])->withInput();
        // }

        $customer->lines()->create(array_merge(
    $validated,
    [
        'added_by' => Auth::id(),
        'attached_at' => now(),
    ]
));

        return redirect()->route('customers.show', $customer)->with('success', 'تم إضافة الخط بنجاح');
    }

    public function edit(Customer $customer, Line $line)
    {
        $plans = Plan::all();
        return view('admin.lines.edit', compact('customer', 'line', 'plans'));
    }

   public function update(Request $request, Customer $customer, Line $line) 
{
    $validated = $request->validate($this->rules($line->id));

    $full_number = $validated['phone_number'];
    $exists = Line::whereRaw("CONCAT(gcode, phone_number) = ?", [$full_number])
                  ->where('id', '!=', $line->id)->exists();

    if ($exists) {
        return back()->withErrors(['phone_number' => 'رقم الهاتف هذا مستخدم بالفعل'])->withInput();
    }

    // إذا تغيّر العميل، حدّث تاريخ الربط
    if (array_key_exists('customer_id', $validated) && $validated['customer_id'] != $line->customer_id) {
        $validated['attached_at'] = now();
    }

    $line->update($validated);

    return redirect()->route('customers.show', $customer)->with('success', 'تم تعديل بيانات الخط');
}


    public function destroy(Customer $customer, Line $line)
    {
        $line->delete();
        return redirect()->route('customers.show', $customer)->with('success', 'تم حذف الخط');
    }

    public function createStandalone()
    {
        $plans = Plan::all();
        $customers = Customer::all();
        return view('admin.lines.create', compact('plans', 'customers'));
    }

    public function storeStandalone(Request $request)
    {
        $validated = $request->validate(array_merge($this->rules(), [
            'customer_id' => 'nullable|exists:customers,id',
            'new_full_name' => 'nullable|string|max:255',
            'new_national_id' => 'nullable|string|size:14|unique:customers,national_id',
        ]));

        $full_number =  $validated['phone_number'];
        $exists = Line::whereRaw("CONCAT(gcode, phone_number) = ?", [$full_number])->exists();

        if ($exists) {
            return back()->withErrors(['phone_number' => 'رقم الهاتف هذا مستخدم بالفعل'])->withInput();
        }

        // $expectedProviders = $this->expectedProviders();
        // if ($expectedProviders[$validated['gcode']] !== $validated['provider']) {
        //     return back()->withErrors(['provider' => 'مزود الخدمة لا يتطابق مع كود الخط المحدد'])->withInput();
        // }

        if (!$request->customer_id && $request->filled(['new_full_name', 'new_national_id'])) {
            $customer = Customer::create([
                'full_name' => $request->new_full_name,
                'national_id' => $request->new_national_id,
            ]);
            $validated['customer_id'] = $customer->id;
        }

        Line::create(array_merge($validated, [
    'added_by' => Auth::id(),
    'attached_at' => now(),
]));


        return redirect()->route('lines.create')->with('success', 'تمت إضافة الخط بنجاح');
    }
public function show(Line $line)
{
    return view('admin.lines.show', compact('line'));
}

    public function editStandalone(Line $line)
    {
        $plans = Plan::all();
        $customers = Customer::all();
        return view('admin.lines.edit', [
            'line' => $line,
            'plans' => $plans,
            'customers' => $customers,
            'customer' => $line->customer,
        ]);
    }
// search
// public function search(Request $request)
// {
//     $term = $request->q;

//     $customers = Customer::where('full_name', 'like', "%$term%")
//         ->orWhere('national_id', 'like', "%$term%")
//         ->select('id', 'full_name', 'national_id')
//         ->limit(20)
//         ->get();

//     return response()->json($customers);
// }

  public function updateStandalone(Request $request, Line $line) 
{
    $validated = $request->validate(array_merge( 
        $this->rules($line->id), 
        [ 
            'customer_id'      => 'nullable|exists:customers,id', 
            'new_full_name'    => 'nullable|string|max:255', 
            'new_national_id'  => 'nullable|string|size:14|unique:customers,national_id', 
        ] 
    ));

    // التأكد من عدم تكرار الرقم الكامل
    $full_number = $validated['phone_number'];
    $exists = Line::whereRaw("CONCAT(gcode, phone_number) = ?", [$full_number])
        ->where('id', '!=', $line->id)
        ->exists();

    if ($exists) {
        return back()->withErrors(['phone_number' => 'رقم الهاتف هذا مستخدم بالفعل'])->withInput();
    }

    // إنشاء عميل جديد إذا لم يتم اختيار واحد وكان الاسم والرقم القومي موجودين
    if (!$request->customer_id && $request->filled(['new_full_name', 'new_national_id'])) {
        $customer = Customer::create([
            'full_name'   => $request->new_full_name,
            'national_id' => $request->new_national_id,
        ]);
        $validated['customer_id'] = $customer->id;
    }

    // تحقق مما إذا كان العميل قد تغيّر
    $customerChanged = isset($validated['customer_id']) && $validated['customer_id'] != $line->customer_id;

    // تحديث بيانات الخط وربطه بالعميل مع تحديث attached_at إذا تغيّر العميل
    $line->update(array_merge($validated, [
        'customer_id' => $validated['customer_id'] ?? null,
        'attached_at' => $customerChanged ? now() : $line->attached_at,
    ]));

    return redirect()->route('lines.all')->with('success', 'تم تحديث بيانات الخط بنجاح');
}



    public function destroyStandalone(Line $line)
    {
        $line->delete();
        return redirect()->route('lines.all')->with('success', 'تم حذف الخط بنجاح');
    }

    private function rules($id = null)
    {
        $uniqueRule = 'unique:lines,phone_number';
        if ($id) {
            $uniqueRule .= "," . $id;
        }

        return [
            'gcode'        => 'required|in:010,011,012,015',
            'phone_number' => ['required', 'digits:11', 'regex:/^[0-9]/', $uniqueRule],
            'provider'     => 'required|in:Vodafone,Etisalat,Orange,WE',
            'line_type'    => 'required|in:prepaid,postpaid',
            'plan_id'      => 'nullable|exists:plans,id',
            'payment_date' => 'nullable|date|before_or_equal:today',
            'package'      => 'nullable|string|max:100',
            'notes'        => 'nullable|string|max:255',
        ];
    }

    private function expectedProviders()
    {
        return [
            '010' => 'Orange',
            '011' => 'Etisalat',
            '012' => 'WE',
            '015' => 'Vodafone',
        ];
    }
}
