<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">فواتير العميل: {{ $customer->full_name }}</h2>
        <h2><div class="mt-4 text-right text-green-700 font-bold text-lg">
    💰 إجمالي الفواتير: {{ number_format($total, 2) }} ج.م
</div>
</h2>
    </x-slot>
<form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    {{-- مزود الخدمة --}}
    <div>
        <label>مزود الخدمة</label>
        <select name="provider[]" multiple class="w-full border p-2 rounded">
            @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $p)
                <option value="{{ $p }}" {{ in_array($p, request('provider', [])) ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
    </div>

    {{-- نوع الخط --}}
    <div>
        <label>نوع الخط</label>
        <select name="line_type[]" multiple class="w-full border p-2 rounded">
            <option value="prepaid" {{ in_array('prepaid', request('line_type', [])) ? 'selected' : '' }}>مدفوع مسبقاً</option>
            <option value="postpaid" {{ in_array('postpaid', request('line_type', [])) ? 'selected' : '' }}>فاتورة</option>
        </select>
    </div>

    {{-- نظام الخط --}}
    <div>
        <label>النظام</label>
        <select name="plan_id[]" multiple class="w-full border p-2 rounded">
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" {{ in_array($plan->id, request('plan_id', [])) ? 'selected' : '' }}>{{ $plan->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- حالة الدفع --}}
    <div>
        <label>حالة الدفع</label>
        <select name="is_paid[]" multiple class="w-full border p-2 rounded">
            <option value="1" {{ in_array('1', request('is_paid', [])) ? 'selected' : '' }}>مدفوع</option>
            <option value="0" {{ in_array('0', request('is_paid', [])) ? 'selected' : '' }}>غير مدفوع</option>
        </select>
    </div>

    {{-- التاريخ من --}}
    <div>
        <label>من تاريخ</label>
        <input type="date" name="from" value="{{ request('from') }}" class="w-full border p-2 rounded">
    </div>

    {{-- التاريخ إلى --}}
    <div>
        <label>إلى تاريخ</label>
        <input type="date" name="to" value="{{ request('to') }}" class="w-full border p-2 rounded">
    </div>

    <div class="md:col-span-3 flex justify-end">
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🔍 فلترة</button>
    </div>
</form>


    <div class="py-6">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
            <table class="min-w-full table-auto border text-center">
                <thead>
                    <tr class="bg-gray-100">
                        <th>الشهر</th>
                        <th>المبلغ</th>
                        <th>تم الدفع</th>
                        <th>تاريخ الدفع</th>
                        <th>تم الدفع بواسطة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_month)->translatedFormat('F Y') }}</td>
                            <td>{{ $invoice->amount }} ج.م</td>
                            <td>{{ $invoice->is_paid ? 'نعم' : 'لا' }}</td>
                            <td>{{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d'): '-' }}</td>
                           <td>{{ $invoice->user?->name ?? '-' }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
    {{ $invoices->appends(request()->query())->links() }}
</div>

        </div>
    </div>
</x-app-layout>
