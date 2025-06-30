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

class RequestController extends Controller

{
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

    return redirect()->route('requests.stop-lines')->with('success', '✅ تم إنشاء طلب تغيير التاريخ بنجاح');
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

    $request->update([
        'status' => $newStatus,
        'done_by' => auth()->id(),
    ]);

    return back()->with('success', '✅ تم تحديث حالة الطلب بنجاح.');
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

    return redirect()->route('requests.stop-lines')->with('success', '✅ تم إنشاء طلب إعادة البيع بنجاح');
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

    if ($request->filled('provider')) {
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
    $codeMap = $this->providerCodeMap();
    $gcode = $codeMap[$line->provider] ?? null;

    if (!$gcode) {
        return back()->withErrors(['provider' => 'مزود الخدمة غير مدعوم']);
    }

    $plans = Plan::where('provider', $gcode)->get();

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

    return redirect()->route('requests.stop-lines')->with('success', '✅ تم تقديم طلب تغيير النظام بنجاح.');
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

    return redirect()->route('requests.stop-lines')->with('success', '✅ تم إنشاء طلب الإيقاف المؤقت بنجاح');
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

    return redirect()->route('requests.stop-lines')->with('success', '✅ تم إنشاء طلب إعادة التشغيل بنجاح.');
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

    return redirect()->route('requests.stop-lines')->with('success', '✅ تم إنشاء طلب تغيير الموزع بنجاح');
}
public function all(HttpRequest $request)
{
    $query = \App\Models\Request::with('line.customer');

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
    $counts = [
        'stop'               => RequestModel::where('request_type', 'stop')->count(),
        'resell'             => RequestModel::where('request_type', 'resell')->count(),
        'change_plan'        => RequestModel::where('request_type', 'change_plan')->count(),
        'change_chip'        => RequestModel::where('request_type', 'change_chip')->count(),
        'pause'              => RequestModel::where('request_type', 'pause')->count(),
        'resume'             => RequestModel::where('request_type', 'resume')->count(),
        'change_date'        => RequestModel::where('request_type', 'change_date')->count(),
        'change_distributor' => RequestModel::where('request_type', 'change_distributor')->count(),
    ];

    $lines = Line::select('id', 'phone_number')->latest()->take(10)->get(); // لاستخدامها للاختيار

    return view('admin.requests.summary', compact('counts', 'lines'));
}
}
