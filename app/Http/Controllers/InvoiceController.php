<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestResumeLine;
use Carbon\Carbon;
 use App\Models\Plan;
class InvoiceController extends Controller
{
   

    public function create(Line $line)
    {
        $line->load('plan', 'customer');
        return view('admin.invoices.create', compact('line'));
    }

   public function store(Request $request, Line $line) 
{ 
    $request->validate([ 
        'months_count' => 'required|integer|min:1', 
    ]); 

    $planPrice = optional($line->plan)->price ?? 0; 
    $months = $request->months_count; 

    $startDate = $line->last_invoice_date 
        ? Carbon::parse($line->last_invoice_date)->addMonth()->startOfMonth() 
        : now()->startOfMonth(); 

    $lastInvoiceMonth = null; 

    for ($i = 0; $i < $months; $i++) { 
        $monthDate = $startDate->copy()->addMonths($i); 

        Invoice::create([ 
            'line_id'       => $line->id, 
            'amount'        => $planPrice, 
            'invoice_month' => $monthDate, 
            'is_paid'       => true, 
            'payment_date'  => now(), 
            'paid_by'       => Auth::id(), 
            'notes'         => $request->notes, 
        ]); 

        $lastInvoiceMonth = $monthDate; 
    } 

    // تحديث تاريخ آخر فاتورة
    if ($lastInvoiceMonth) { 
        $line->update([ 
            'last_invoice_date' => $lastInvoiceMonth, 
        ]); 
    } 

    // ✅ إنشاء طلب إعادة تشغيل إذا كانت الشروط مناسبة
    $resumeExists = \App\Models\Request::where('line_id', $line->id)
    ->where('request_type', 'resume')
    ->whereDate('created_at', now()->toDateString())
    ->exists();
//     Log::debug('Resume auto request check', [
//     'line_status'        => $line->status,
//     'last_invoice_date'  => $lastInvoiceMonth?->toDateString(),
//     'now'                => now()->toDateString(),
//     'should_create'      => $line->status === 'inactive' && $lastInvoiceMonth?->greaterThan(now()),
// ]);

    if (
    $line->status === 'inactive' &&
    $lastInvoiceMonth &&
    $lastInvoiceMonth->greaterThan(now()) &&
    !$resumeExists
) {

    $resumeRequest = \App\Models\Request::create([
        'line_id'      => $line->id,
        'customer_id'  => $line->customer_id,
        'request_type' => 'resume',
        'status'       => 'pending',
        'requested_by' => Auth::id(),
    ]);

    $resumeDetails = \App\Models\RequestResumeLine::create([
        'request_id'   => $resumeRequest->id,
        'reason'       => 'تم دفع الفاتورة',
        'comment'      => 'تم إنشاء الطلب بواسطة النظام تلقائياً',
    ]);

    // dd([
    //     'line_status'        => $line->status,
    //     'last_invoice_date'  => $lastInvoiceMonth,
    //     'now'                => now(),
    //     'resume_request'     => $resumeRequest,
    //     'resume_request_det' => $resumeDetails,
    // ]);
}

    return redirect()->route('invoices.index', ['line' => $line->id])
                     ->with('success', '✅ تم دفع الفواتير بنجاح.');
}

public function index(Request $request)
{
    $query = Invoice::with(['line.customer', 'user'])
        ->whereHas('line');

    if ($request->filled('provider')) {
    $query->whereHas('line', fn($q) => $q->whereIn('provider', $request->provider));
}

if ($request->filled('line_type')) {
    $query->whereHas('line', fn($q) => $q->whereIn('line_type', $request->line_type));
}

if ($request->filled('plan_id')) {
    $query->whereHas('line', fn($q) => $q->whereIn('plan_id', $request->plan_id));
}

if ($request->filled('is_paid')) {
    $query->whereIn('is_paid', $request->is_paid);
}

if ($request->filled('from')) {
    $query->whereDate('invoice_month', '>=', $request->from);
}
if ($request->filled('to')) {
    $query->whereDate('invoice_month', '<=', $request->to);
}

    $invoices = $query->latest('invoice_month')->paginate(20);
    $total = $query->sum('amount');

    $plans = Plan::all(); // للفلترة بنظام

    return view('admin.invoices.index', compact('invoices', 'total', 'plans'));
}


    public function customerInvoices(Request $request, Customer $customer)
{
    $query = Invoice::whereHas('line', function ($q) use ($customer) {
        $q->where('customer_id', $customer->id);
    });

     if ($request->filled('provider')) {
    $query->whereHas('line', fn($q) => $q->whereIn('provider', $request->provider));
}

if ($request->filled('line_type')) {
    $query->whereHas('line', fn($q) => $q->whereIn('line_type', $request->line_type));
}

if ($request->filled('plan_id')) {
    $query->whereHas('line', fn($q) => $q->whereIn('plan_id', $request->plan_id));
}

if ($request->filled('is_paid')) {
    $query->whereIn('is_paid', $request->is_paid);
}

if ($request->filled('from')) {
    $query->whereDate('invoice_month', '>=', $request->from);
}
if ($request->filled('to')) {
    $query->whereDate('invoice_month', '<=', $request->to);
}

    $invoices = $query->with(['line', 'user'])->latest('invoice_month')->paginate(10);
    $total = $query->sum('amount');

    return view('admin.invoices.customer', compact('customer', 'invoices', 'total'));
}




public function lineInvoices(Request $request, Line $line) 
{
    $query = $line->invoices()->with('user');

    if ($request->filled('provider')) {
        $query->whereHas('line', fn($q) => $q->whereIn('provider', $request->provider));
    }

    if ($request->filled('line_type')) {
        $query->whereHas('line', fn($q) => $q->whereIn('line_type', $request->line_type));
    }

    if ($request->filled('plan_id')) {
        $query->whereHas('line', fn($q) => $q->whereIn('plan_id', $request->plan_id));
    }

    if ($request->filled('is_paid')) {
        $query->whereIn('is_paid', $request->is_paid);
    }

    if ($request->filled('from')) {
        $query->whereDate('invoice_month', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('invoice_month', '<=', $request->to);
    }

    $invoices = $query->latest('invoice_month')->paginate(20);
    $total = $query->sum('amount');
    $plans = Plan::all(); // ✅ إضافة هذا السطر

    return view('admin.invoices.by-line', compact('line', 'invoices', 'total', 'plans'));
}


}
