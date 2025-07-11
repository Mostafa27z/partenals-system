<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Request as RequestModel;
use App\Models\User;
use App\Models\RequestResell;
use App\Models\RequestResumeLine;
use App\Models\Line;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestChangeDistributor;
use App\Models\Plan;
use App\Models\RequestChangePlan;
use App\Models\RequestChangeChip;
use App\Models\RequestPauseLine;
// use App\Models\LineRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RequestsExport;
use App\Models\RequestStopLine;
use App\Models\RequestResellLine;
use App\Models\RequestChangeDate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ErrorExport implements FromCollection, WithHeadings
{
    protected $rows;
    protected $headings;

    public function __construct(array $rows, array $headings)
    {
        $this->rows = collect($rows);
        $this->headings = $headings;
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}

class RequestController extends Controller

{
    


public function importPauseRequests(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $reason = trim($row[1] ?? '');
        $comment = trim($row[2] ?? '');

        $line = Line::where('phone_number', $phone)->first();

        if (!$line) {
            $errors[] = "❌ الصف " . ($index + 1) . ": رقم الهاتف $phone غير موجود.";
            continue;
        }

        $mainRequest = LineRequest::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'pause',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestPauseLine::create([
            'request_id' => $mainRequest->id,
            'reason'     => $reason,
            'comment'    => $comment,
        ]);

        $imported++;
    }

    if (count($errors)) {
    $filename = 'import_errors_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
        $filename
    );
}

return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");

}

public function importResumeRequests(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $reason = trim($row[1] ?? '');
        $comment = trim($row[2] ?? '');

        $line = Line::where('phone_number', $phone)->first();

        if (!$line) {
            $errors[] = "❌ الصف " . ($index + 1) . ": رقم الهاتف $phone غير موجود.";
            continue;
        }

        $mainRequest = LineRequest::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'resume',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestResumeLine::create([
            'request_id' => $mainRequest->id,
            'reason'     => $reason,
            'comment'    => $comment,
        ]);

        $imported++;
    }

    if (count($errors)) {
    $filename = 'import_errors_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
        $filename
    );
}

return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");

}


public function importChangeDateRequests(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $newDate = trim($row[1] ?? '');
        $reason = trim($row[2] ?? '');
        $comment = trim($row[3] ?? '');

        $line = Line::where('phone_number', $phone)->first();

        if (!$line) {
            $errors[] = "❌ الصف " . ($index + 1) . ": رقم الهاتف $phone غير موجود.";
            continue;
        }

        if (!$newDate || !\Carbon\Carbon::canBeCreatedFromFormat($newDate, 'Y-m-d')) {
            $errors[] = "❌ الصف " . ($index + 1) . ": التاريخ غير صالح.";
            continue;
        }

        $mainRequest = LineRequest::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'change_date',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestChangeDate::create([
            'request_id'   => $mainRequest->id,
            'new_date'     => $newDate,
            'reason'       => $reason,
            'comment'      => $comment,
        ]);

        $imported++;
    }

    if (count($errors)) {
    $filename = 'import_errors_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
        $filename
    );
}

return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");

}


public function importChangeDistributorRequests(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $newDistributor = trim($row[1] ?? '');
        $reason = trim($row[2] ?? '');
        $comment = trim($row[3] ?? '');

        $line = Line::where('phone_number', $phone)->first();

        if (!$line) {
            $errors[] = "❌ الصف " . ($index + 1) . ": رقم الهاتف $phone غير موجود.";
            continue;
        }

        if (!$newDistributor) {
            $errors[] = "❌ الصف " . ($index + 1) . ": اسم الموزع الجديد مفقود.";
            continue;
        }

        $mainRequest = LineRequest::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'change_distributor',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestChangeDistributor::create([
            'request_id'       => $mainRequest->id,
            'new_distributor'  => $newDistributor,
            'reason'           => $reason,
            'comment'          => $comment,
        ]);

        $imported++;
    }

    if (count($errors)) {
    $filename = 'import_errors_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
        $filename
    );
}

return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");

}


public function importChangeChipRequests(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;

        $phone = trim($row[0] ?? '');
        $newSerial = trim($row[1] ?? '');
        $reason = trim($row[2] ?? '');
        $comment = trim($row[3] ?? '');

        $line = Line::where('phone_number', $phone)->first();

        if (!$line) {
            $errors[] = "❌ الصف " . ($index + 1) . ": رقم الهاتف $phone غير موجود.";
            continue;
        }

        if (!$newSerial) {
            $errors[] = "❌ الصف " . ($index + 1) . ": رقم الشريحة الجديدة غير موجود.";
            continue;
        }

        $mainRequest = LineRequest::create([
            'line_id' => $line->id,
            'customer_id' => $line->customer_id,
            'request_type' => 'change_chip',
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        RequestChangeChip::create([
            'request_id' => $mainRequest->id,
            'new_serial' => $newSerial,
            'reason' => $reason,
            'comment' => $comment,
        ]);

        $imported++;
    }

if (count($errors)) {
    $filename = 'import_errors_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
        $filename
    );
}

return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}



public function importChangePlanRequests(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue; // Skip header row

        $phone = trim($row[0] ?? '');
        $newPlanName = trim($row[1] ?? '');
        $reason = trim($row[2] ?? '');
        $comment = trim($row[3] ?? '');

        $line = Line::where('phone_number', $phone)->first();

        if (!$line) {
            $errors[] = "❌ الصف " . ($index + 1) . ": رقم الهاتف $phone غير موجود.";
            continue;
        }

        $newPlan = Plan::where('name', $newPlanName)->first();

        if (!$newPlan) {
            $errors[] = "❌ الصف " . ($index + 1) . ": النظام $newPlanName غير موجود.";
            continue;
        }

        // إنشاء الطلب
        $mainRequest = LineRequest::create([
            'line_id' => $line->id,
            'customer_id' => $line->customer_id,
            'request_type' => 'change_plan',
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        // التفاصيل
        RequestChangePlan::create([
            'request_id' => $mainRequest->id,
            'new_plan_id' => $newPlan->id,
            'reason' => $reason,
            'comment' => $comment,
        ]);

        $imported++;
    }

if (count($errors)) {
    $filename = 'import_errors_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
        $filename
    );
}

return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}



public function importStopRequests(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx'
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue; // skip header

        $phone = trim($row[0] ?? '');
        $reason = trim($row[1] ?? '');
        $comment = trim($row[2] ?? '');

        $line = Line::where('phone_number', $phone)->first();

        if (!$line) {
            $errors[] = "الصف " . ($index + 1) . " - رقم الخط غير موجود: $phone";
            continue;
        }

        // أنشئ الطلب الرئيسي
        $mainRequest = LineRequest::create([
            'line_id'      => $line->id,
            'customer_id'  => $line->customer_id,
            'request_type' => 'stop',
            'status'       => 'pending',
            'requested_by' => auth()->id(),
        ]);

        // أنشئ تفاصيل الطلب
        RequestStopLine::create([
            'request_id' => $mainRequest->id,
            'reason'     => $reason,
            'comment'    => $comment,
        ]);

        $imported++;
    }

if (count($errors)) {
    $filename = 'import_errors_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
        $filename
    );
}

return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}




public function importResellRequests(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx',
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))->first();
    $imported = 0;
    $errors = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) continue; // skip header row

        $phone = trim($row[0] ?? '');
        $type = trim($row[1] ?? '');
        $oldSerial = trim($row[2] ?? '');
        $newSerial = trim($row[3] ?? '');
        $comment = trim($row[4] ?? '');

        $line = Line::where('phone_number', $phone)->first();

        if (!$line) {
            $errors[] = "❌ الصف " . ($index + 1) . ": الرقم $phone غير موجود";
            continue;
        }

        if (!in_array($type, ['chip', 'branch'])) {
            $errors[] = "❌ الصف " . ($index + 1) . ": نوع غير صحيح: $type";
            continue;
        }

        if ($type === 'chip' && !$newSerial) {
            $errors[] = "❌ الصف " . ($index + 1) . ": يجب إدخال الرقم التسلسلي الجديد";
            continue;
        }

        // إنشاء الطلب
        $mainRequest = LineRequest::create([
            'line_id' => $line->id,
            'customer_id' => $line->customer_id,
            'request_type' => 'resell',
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        // التفاصيل
        RequestResellLine::create([
            'request_id' => $mainRequest->id,
            'type' => $type,
            'old_serial' => $oldSerial,
            'new_serial' => $newSerial,
            'comment' => $comment,
        ]);

        $imported++;
    }

if (count($errors)) {
    $filename = 'import_errors_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(
        new ErrorExport($errors, ['رقم الهاتف', 'السبب', 'ملاحظات', 'الخطأ']),
        $filename
    );
}

return redirect()->back()->with('success', "✅ تم استيراد $imported طلب بنجاح.");
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
public function stopLineRequests(HttpRequest $request)
{
    $query = RequestModel::where('request_type', 'stop')
        ->with(['line.customer', 'stopDetails', 'requestedBy', 'doneBy']);

    // فلترة حسب الرقم القومي
    if ($request->filled('nid')) {
        $query->whereHas('line.customer', fn($q) => $q->where('national_id', 'like', '%' . $request->nid . '%'));
    }

    // فلترة حسب رقم الهاتف
    if ($request->filled('phone')) {
        $query->whereHas('line', fn($q) => $q->where('phone_number', 'like', '%' . $request->phone . '%'));
    }

    // فلترة حسب مزود الخدمة
    if ($request->filled('provider')) {
        $query->whereHas('line', fn($q) => $q->where('provider', $request->provider));
    }

    // فلترة حسب الموزع
    if ($request->filled('distributor')) {
        $query->whereHas('line', fn($q) => $q->where('distributor', 'like', '%' . $request->distributor . '%'));
    }

    // فلترة حسب من أنشأ الطلب
    if ($request->filled('requested_by')) {
        $query->where('requested_by', $request->requested_by);
    }

    // فلترة حسب من نفذ الطلب
    if ($request->filled('done_by')) {
        $query->where('done_by', $request->done_by);
    }

    // فلترة حسب تاريخ آخر فاتورة
    if ($request->filled('from')) {
        $query->whereHas('stopDetails', fn($q) => $q->whereDate('last_invoice_date', '>=', $request->from));
    }

    if ($request->filled('to')) {
        $query->whereHas('stopDetails', fn($q) => $q->whereDate('last_invoice_date', '<=', $request->to));
    }

    $requests = $query->latest()->paginate(20);
    $users = User::select('id', 'name')->get();

    return view('admin.requests.stop-lines', compact('requests', 'users'));
}
public function createStop(Line $line)
{
    return view('admin.requests.stop-create', compact('line'));
}
public function storeStop(HttpRequest $request)
{
    $request->validate([
        'line_id'     => 'required|exists:lines,id',
        'customer_id' => 'required|exists:customers,id',
        'reason'      => 'required|string|max:255',
        'comment'     => 'nullable|string|max:1000',
    ]);

    $requestRecord = \App\Models\Request::create([
        'line_id'      => $request->line_id,
        'customer_id'  => $request->customer_id,
        'request_type' => 'stop',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    \App\Models\RequestStopLine::create([
        'request_id' => $requestRecord->id,
        'reason'     => $request->reason,
        'comment'    => $request->comment,
    ]);

    return redirect()->route('requests.all')->with('success', '✅ تم إنشاء طلب الإيقاف النهائي بنجاح.');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HttpRequest $request)
    {
        //
    }
public function createChangeDate(Line $line)
{
    return view('admin.requests.create-change-date', compact('line'));
}

public function storeChangeDate(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id'      => 'required|exists:lines,id',
        'new_date'     => 'required|date|after:1900-01-01',
        'reason'       => 'nullable|string|max:255',
    ]);

    $line = Line::findOrFail($validated['line_id']);

    // إنشاء الطلب الأساسي
    $requestModel = \App\Models\Request::create([
        'line_id'      => $line->id,
        'customer_id'  => $line->customer_id,
        'request_type' => 'change_date',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    // حفظ التفاصيل
    \App\Models\RequestChangeDate::create([
        'request_id'   => $requestModel->id,
        'current_date' => $line->last_invoice_date,
        'new_date'     => $validated['new_date'],
        'reason'       => $validated['reason'],
    ]);

    return back()->with('success', '✅ تم عمل الطلب بنجاح.');
}

    /**
     * Display the specified resource.
     */
    // public function show(HttpRequest $request)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HttpRequest $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HttpRequest $request)
    {
        //
    }
    

public function updateStatus(HttpRequest $httpRequest, RequestModel $request) 
{
    $newStatus = $httpRequest->status;
    $oldStatus = $httpRequest->old_status;

    if ($request->status !== $oldStatus) {
        return back()->withErrors([
            'status' => "❌ الحالة الحالية هي {$request->status}، ولا تتطابق مع الحالة السابقة المدخلة ({$oldStatus})"
        ]);
    }

    // ✅ لو الحالة الجديدة "done" نفذ التأثير الخاص بالطلب
    if ($newStatus === 'done') {
        match ($request->request_type) {
            'change_plan' => $this->applyChangePlan($request),
            'change_chip' => $this->applyChangeChip($request),
            'change_distributor' => $this->applyChangeDistributor($request),
            'stop' => $this->applyStopLine($request),
            'pause' => $this->applyPauseLine($request),
            'resume' => $this->applyResumeLine($request),
            'resell' => $this->applyResell($request),
            'change_date' => $this->applyChangeDate($request),
            default => null
        };
    }

    $request->update([
        'status' => $newStatus,
        'done_by' => auth()->id(),
    ]);

    return back()->with('success', '✅ تم تحديث حالة الطلب بنجاح.');
}
protected function applyChangePlan(RequestModel $request)
{
    $data = RequestChangePlan::where('request_id', $request->id)->first();
    if ($data && $data->new_plan_id) {
        $data->line->update([
            'plan_id' => $data->new_plan_id,
        ]);
    }
}
protected function applyChangeDistributor(RequestModel $request)
{
    $data = RequestChangeDistributor::where('request_id', $request->id)->first();
    if ($data && $data->new_distributor) {
        $data->line->update([
            'distributor' => $data->new_distributor,
        ]);
    }
}
protected function applyStopLine(RequestModel $request) 
{
    $data = RequestStopLine::where('request_id', $request->id)->first(); 

    if ($data) {
        $data->line->update([
            'status' => 'inactive',
        ]);
    }
}

protected function applyPauseLine(RequestModel $request)
{
    $data = RequestPauseLine::where('request_id', $request->id)->first();
    if ($data) {
        $data->line->update([
            'status' => 'inactive',
        ]);
    }
}
protected function applyResumeLine(RequestModel $request)
{
    $data = RequestResumeLine::where('request_id', $request->id)->first();
    if ($data) {
        $data->line->update([
            'status' => 'active',
        ]);
    }
}
protected function applyResell(RequestModel $request)
{
    $data = RequestResell::where('request_id', $request->id)->first();
    if (!$data) return;

    // إن وجد الرقم القومي، حاول ربطه بعميل
    $customer = null;
    if ($data->national_id) {
        $customer = Customer::where('national_id', $data->national_id)->first();
        if (!$customer && $data->full_name) {
            $customer = Customer::create([
                'full_name'   => $data->full_name,
                'national_id' => $data->national_id,
            ]);
        }
    }

    // تحديث الخط
    $data->line->update([
        'customer_id' => $customer?->id,
        'phone_number' => $data->new_serial ?? $data->line->phone_number,
        'gcode' => $data->new_serial ? substr($data->new_serial, 0, 3) : $data->line->gcode,
        'attached_at' => now(),
    ]);
}
protected function applyChangeDate(RequestModel $request)
{
    $data = RequestChangeDate::where('request_id', $request->id)->first();
    if ($data && $data->new_date) {
        $data->line->update([
            'last_invoice_date' => $data->new_date,
        ]);
    }
}
protected function applyChangeChip(RequestModel $request)
{
    $data = RequestChangeChip::where('request_id', $request->id)->first();
    if ($data && $data->new_serial) {
        $data->line->update([
            'phone_number' => $data->new_serial,
            'gcode' => substr($data->new_serial, 0, 3), // تحديث المقدمة تلقائياً
        ]);
    }
}



public function createResell(Line $line)
{
    $line->load('customer');
    return view('admin.requests.create-resell', compact('line'));
}







public function storeResell(HttpRequest $request)
{
    $validated = $request->validate([
    'line_id'      => 'required|exists:lines,id',
    'resell_type'  => 'required|in:chip,branch',
    'old_serial'   => 'nullable|regex:/^\d+$/|size:19',
    'new_serial'   => 'required_if:resell_type,chip|regex:/^\d+$/|size:19',
    'request_date' => 'required|date',
    'comment'      => 'nullable|string|max:1000',
    'full_name'    => 'nullable|required_if:resell_type,branch|string|max:255',
    'national_id'  => 'nullable|required_if:resell_type,branch|digits:14',
], [
    'resell_type.required'     => 'يجب اختيار نوع إعادة البيع.',
    'new_serial.required_if'   => 'يجب إدخال المسلسل الجديد عند اختيار نوع الشريحة.',
    'new_serial.regex'         => 'المسلسل الجديد يجب أن يحتوي على أرقام فقط.',
    'old_serial.regex'         => 'المسلسل القديم يجب أن يحتوي على أرقام فقط.',
    'full_name.required_if'    => 'يجب إدخال الاسم عند اختيار النوع فرع.',
    'national_id.required_if'  => 'يجب إدخال الرقم القومي عند اختيار النوع فرع.',
    'national_id.digits'       => 'الرقم القومي يجب أن يكون 14 رقمًا.',
]);


    // 🧩 إنشاء الطلب الأساسي
    $requestRecord = RequestModel::create([
        'line_id'      => $validated['line_id'],
        'customer_id'  => \App\Models\Line::find($validated['line_id'])->customer_id,
        'request_type' => 'resell',
        'status'       => 'pending',
        'requested_by' => Auth::id(),
    ]);

    // 🧩 حفظ تفاصيل إعادة البيع
    RequestResell::create([
        'request_id'   => $requestRecord->id,
        'resell_type'  => $validated['resell_type'],
        'old_serial'   => $validated['old_serial'],
        'new_serial'   => $validated['new_serial'] ?? null,
        'request_date' => $validated['request_date'],
        'full_name'    => $validated['full_name'] ?? null,
        'national_id'  => $validated['national_id'] ?? null,
        'comment'      => $validated['comment'],
    ]);

     redirect()->route('requests.stop-lines')->with('success', '✅ تم إنشاء طلب إعادة البيع بنجاح');
}
public function resellRequests(HttpRequest $request)
{
    $query = Request::where('request_type', 'resell')
        ->with(['line.customer', 'resellDetails', 'requestedBy', 'doneBy']);

    if ($request->filled('resell_type')) {
        $query->whereHas('resellDetails', fn($q) =>
            $q->where('resell_type', $request->resell_type));
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('from')) {
        $query->whereHas('resellDetails', fn($q) =>
            $q->whereDate('request_date', '>=', $request->from));
    }

    if ($request->filled('to')) {
        $query->whereHas('resellDetails', fn($q) =>
            $q->whereDate('request_date', '<=', $request->to));
    }

    if ($request->filled('name')) {
        $query->whereHas('line.customer', fn($q) =>
            $q->where('full_name', 'like', '%' . $request->name . '%'));
    }

    if ($request->filled('nid')) {
        $query->whereHas('line.customer', fn($q) =>
            $q->where('national_id', 'like', '%' . $request->nid . '%'));
    }

    if ($request->filled('provider')) {return
        $query->whereHas('line', fn($q) =>
            $q->where('provider', 'like', '%' . $request->provider . '%'));
    }

    $requests = $query->latest()->paginate(20);

    return view('admin.requests.resell-index', compact('requests'));
}
public function resellDetails(RequestModel $request)
{
    $request->load(['line.customer', 'resellDetails', 'requestedBy', 'doneBy']);

    return view('admin.requests.resell-show', ['requestModel' => $request]);
}


public function chooseLineForResell()
{
    $lines = \App\Models\Line::with('customer')->latest()->paginate(20);
    return view('admin.requests.choose-line-resell', compact('lines'));
}
private function providerCodeMap()
{
    return [
        'Vodafone' => '010',
        'Etisalat' => '011',
        'WE'       => '012',
        'Orange'   => '015',
    ];
}

public function createChangePlan(Line $line) 
{
    $plans = Plan::where('provider', $line->provider)->get();

    return view('admin.requests.create-change-plan', compact('line', 'plans'));
}


public function storeChangePlan(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id' => 'required|exists:lines,id',
        'new_plan_id' => 'required|exists:plans,id',
        'comment' => 'nullable|string|max:1000',
    ]);

    $line = Line::findOrFail($validated['line_id']);

    $mainRequest = RequestModel::create([
        'line_id' => $line->id,
        'customer_id' => $line->customer_id,
        'request_type' => 'change_plan',
        'status' => 'pending',
        'requested_by' => auth()->id(),
    ]);

    RequestChangePlan::create([
        'request_id' => $mainRequest->id,
        'new_plan_id' => $validated['new_plan_id'],
        'comment' => $validated['comment'] ?? null,
    ]);

    return back()->with('success', '✅ تم عمل الطلب بنجاح.');
}
// In RequestController.php



public function createChangeChip(Line $line)
{
    return view('admin.requests.create-change-chip', compact('line'));
}

public function storeChangeChip(HttpRequest $request)
{
    $validated = $request->validate([
    'line_id'      => 'required|exists:lines,id',
    'change_type'  => 'required|in:chip,branch',
    'old_serial'   => 'nullable|regex:/^\d+$/|size:19',
    'new_serial'   => 'required_if:change_type,chip|regex:/^\d+$/|size:19',
    'request_date' => 'required|date',
    'comment'      => 'nullable|string|max:1000',
    'full_name'    => 'nullable|required_if:change_type,branch|string|max:255',
    'national_id'  => 'nullable|required_if:change_type,branch|digits:14',
], [
    'change_type.required'     => 'يجب اختيار نوع  التغيير.',
    'new_serial.required_if'   => 'يجب إدخال المسلسل الجديد عند اختيار نوع الشريحة.',
    'new_serial.regex'         => 'المسلسل الجديد يجب أن يحتوي على أرقام فقط.',
    'old_serial.regex'         => 'المسلسل القديم يجب أن يحتوي على أرقام فقط.',
    'full_name.required_if'    => 'يجب إدخال الاسم عند اختيار النوع فرع.',
    'national_id.required_if'  => 'يجب إدخال الرقم القومي عند اختيار النوع فرع.',
    'national_id.digits'       => 'الرقم القومي يجب أن يكون 14 رقمًا.',
]);

    $line = \App\Models\Line::findOrFail($validated['line_id']);

    $requestModel = \App\Models\Request::create([
        'line_id'      => $line->id,
        'customer_id'  => $line->customer_id,
        'request_type' => 'change_chip',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    \App\Models\RequestChangeChip::create([
        'request_id'   => $requestModel->id,
        'change_type'  => $validated['change_type'],
        'old_serial'   => $validated['old_serial'] ?? null,
        'new_serial'   => $validated['new_serial'] ?? null,
        'full_name'    => $validated['full_name'] ?? null,
        'national_id'  => $validated['national_id'] ?? null,
        'request_date' => $validated['request_date'],
        'comment'      => $validated['comment'] ?? null,
    ]);

    return redirect()->route('requests.stop-lines')->with('success', '✅ تم إنشاء طلب تغيير الشريحة بنجاح');
}
public function createPause($lineId)
{
    $line = Line::with('customer')->findOrFail($lineId);
    return view('admin.requests.create-pause-request', compact('line'));
}
public function storePause(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id'     => 'required|exists:lines,id',
        'reason'      => 'required|string|max:255',
        'comment'     => 'nullable|string|max:1000',
    ]);

    $line = Line::findOrFail($validated['line_id']);

    $requestModel = RequestModel::create([
        'line_id'      => $line->id,
        'customer_id'  => $line->customer_id,
        'request_type' => 'pause',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    RequestPauseLine::create([
        'request_id' => $requestModel->id,
        'reason'     => $validated['reason'],
        'comment'    => $validated['comment'],
    ]);

    return back()->with('success', '✅ تم عمل الطلب بنجاح.');
}
public function createResume($lineId)
{
    $line = Line::with('customer')->findOrFail($lineId);
    return view('admin.requests.create-resume', compact('line'));
}

public function storeResume(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id' => 'required|exists:lines,id',
        'reason' => 'required|string|max:255',
        'comment' => 'nullable|string|max:1000',
    ]);

    $line = Line::findOrFail($validated['line_id']);

    $req = RequestModel::create([
        'line_id' => $line->id,
        'customer_id' => $line->customer_id,
        'request_type' => 'resume',
        'status' => 'pending',
        'requested_by' => auth()->id(),
    ]);

    RequestResumeLine::create([
        'request_id' => $req->id,
        'reason' => $validated['reason'],
        'comment' => $validated['comment'],
    ]);

     return back()->with('success', '✅ تم عمل الطلب بنجاح.');
}



public function createChangeDistributor(Line $line)
{
    return view('admin.requests.create-change-distributor', compact('line'));
}

public function storeChangeDistributor(HttpRequest $request)
{
    $validated = $request->validate([
        'line_id'         => 'required|exists:lines,id',
        'new_distributor' => 'required|string|max:255',
        'reason'          => 'nullable|string|max:1000',
    ]);

    $line = Line::findOrFail($validated['line_id']);

    $requestModel = RequestModel::create([
        'line_id'      => $line->id,
        'customer_id'  => $line->customer_id,
        'request_type' => 'change_distributor',
        'status'       => 'pending',
        'requested_by' => auth()->id(),
    ]);

    RequestChangeDistributor::create([
        'request_id'      => $requestModel->id,
        'old_distributor' => $line->distributor,
        'new_distributor' => $validated['new_distributor'],
        'reason'          => $validated['reason'],
    ]);

    return back()->with('success', '✅ تم عمل الطلب بنجاح.');
}


public function history(HttpRequest $request)
{
    $query = \App\Models\Request::with('line.customer')
        ->where('status', 'done'); // ✅ عرض الطلبات المنتهية فقط

    // فلترة بالرقم
    if ($request->filled('phone')) {
        $query->whereHas('line', fn($q) => $q->where('phone_number', 'like', "%{$request->phone}%"));
    }

    // فلترة بالرقم القومي
    if ($request->filled('nid')) {
        $query->whereHas('line.customer', fn($q) => $q->where('national_id', 'like', "%{$request->nid}%"));
    }

    // فلترة بالنوع
    if ($request->filled('type')) {
        $query->where('request_type', $request->type);
    }

    // فلترة بالتاريخ
    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }
    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    // فلترة بالمشغل
    if ($request->filled('provider')) {
        $query->whereHas('line', fn($q) => $q->where('provider', 'like', "%{$request->provider}%"));
    }

    $requests = $query->latest()->paginate(20);

    return view('admin.requests.history', compact('requests'));
}

public function all(HttpRequest $request)
{
    $query = \App\Models\Request::with('line.customer')->where('status', '!=', 'done');

    // فلترة بالرقم
    if ($request->filled('phone')) {
        $query->whereHas('line', fn($q) => $q->where('phone_number', 'like', "%{$request->phone}%"));
    }

    // فلترة بالرقم القومي
    if ($request->filled('nid')) {
        $query->whereHas('line.customer', fn($q) => $q->where('national_id', 'like', "%{$request->nid}%"));
    }

    // فلترة بالنوع
    if ($request->filled('type')) {
        $query->where('request_type', $request->type);
    }

    // فلترة بالتاريخ
    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }
    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    // فلترة بالمشغل
    if ($request->filled('provider')) {
        $query->whereHas('line', fn($q) => $q->where('provider', 'like', "%{$request->provider}%"));
    }

    $requests = $query->latest()->paginate(20);

    return view('admin.requests.all', compact('requests'));
}

public function bulkUpdate(HttpRequest $request)
{
    $request->validate([
        'selected_requests' => 'required|array',
        'status' => 'required|in:pending,inprogress,done,cancelled',
    ]);

    \App\Models\Request::whereIn('id', $request->selected_requests)
        ->update([
            'status' => $request->status,
            'done_by' => auth()->id(),
        ]);

    return back()->with('success', '✅ تم تحديث حالة الطلبات المحددة بنجاح.');
}

public function bulkAction(HttpRequest $request)
{
    $request->validate([
        'selected_requests' => 'required|array',
        'selected_requests.*' => 'exists:requests,id',
        'new_status' => 'nullable|in:pending,inprogress,done,cancelled',
        'action' => 'required|in:change_status,export,change_and_export',
    ]);

    $requests = \App\Models\Request::whereIn('id', $request->selected_requests)->get();

    if ($request->action === 'change_status' || $request->action === 'change_and_export') {
        foreach ($requests as $r) {
            $r->update([
                'status' => $request->new_status,
                'done_by' => auth()->id()
            ]);
        }
    }

    if ($request->action === 'export' || $request->action === 'change_and_export') {
        return Excel::download(new RequestsExport($requests), 'selected_requests.xlsx');
    }

    return back()->with('success', '✅ تم تنفيذ العملية بنجاح.');
}

public function show(RequestModel $request)
{
    $request->load([
        'line.customer',
        'requestedBy',
        'doneBy',
        'stopDetails',
        'resellDetails',
        'changeChip',
        'pause',
        'resume',
        'changePlan',
        'changeDistributor',
        'changeDate',
    ]);

    return view('admin.requests.show', compact('request'));
}

public function summary()
{
   $types = ['resell', 'change_plan', 'change_chip', 'pause', 'resume', 'change_date', 'change_distributor', 'stop'];

$counts = [];
foreach ($types as $type) {
    $counts[$type] = [
        'today' => \App\Models\Request::where('request_type', $type)
                        ->whereDate('created_at', now()->toDateString())
                        ->count(),
        'total' => \App\Models\Request::where('request_type', $type)->count(),
    ];
}

return view('admin.requests.summary', compact('counts'));

}
}
