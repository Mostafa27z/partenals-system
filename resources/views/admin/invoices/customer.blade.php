<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('messages.Customer Invoices') }}: {{ $customer->full_name }}
        </h2>
        <div class="mt-4 text-right text-green-700 font-bold text-lg">
            💰 {{ __('messages.Total Invoices') }}: {{ number_format($total, 2) }} {{ __('messages.EGP') }}
        </div>
    </x-slot>

    <form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-white rounded shadow max-w-7xl mx-auto">
        {{-- مزود الخدمة --}}
        <div>
            <label class="block font-semibold mb-1">{{ __('messages.Provider') }}</label>
            <select name="provider[]" multiple class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-300">
                @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $p)
                    <option value="{{ $p }}" {{ in_array($p, request('provider', [])) ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>

        {{-- نوع الخط --}}
        <div>
            <label class="block font-semibold mb-1">{{ __('messages.Line Type') }}</label>
            <select name="line_type[]" multiple class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-300">
                <option value="prepaid" {{ in_array('prepaid', request('line_type', [])) ? 'selected' : '' }}>{{ __('messages.Prepaid') }}</option>
                <option value="postpaid" {{ in_array('postpaid', request('line_type', [])) ? 'selected' : '' }}>{{ __('messages.Postpaid') }}</option>
            </select>
        </div>

        {{-- نظام الخط --}}
        <div>
            <label class="block font-semibold mb-1">{{ __('messages.Plan') }}</label>
            <select name="plan_id[]" multiple class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-300">
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ in_array($plan->id, request('plan_id', [])) ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- حالة الدفع --}}
        <div>
            <label class="block font-semibold mb-1">{{ __('messages.Payment Status') }}</label>
            <select name="is_paid[]" multiple class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-300">
                <option value="1" {{ in_array('1', request('is_paid', [])) ? 'selected' : '' }}>{{ __('messages.Paid') }}</option>
                <option value="0" {{ in_array('0', request('is_paid', [])) ? 'selected' : '' }}>{{ __('messages.Unpaid') }}</option>
            </select>
        </div>

        {{-- التاريخ من --}}
        <div>
            <label class="block font-semibold mb-1">{{ __('messages.Date From') }}</label>
            <input type="date" name="from" value="{{ request('from') }}" class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-300">
        </div>

        {{-- التاريخ إلى --}}
        <div>
            <label class="block font-semibold mb-1">{{ __('messages.Date To') }}</label>
            <input type="date" name="to" value="{{ request('to') }}" class="w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-300">
        </div>

        <div class="md:col-span-3 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                🔍 {{ __('messages.Filter') }}
            </button>
        </div>
    </form>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full table-auto text-center text-gray-700">
                <thead class="bg-gray-100 font-semibold">
                    <tr>
                        <th class="px-4 py-2">{{ __('messages.Month') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Amount') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Paid') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Payment Date') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Paid By') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($invoice->invoice_month)->translatedFormat('F Y') }}</td>
                            <td class="px-4 py-2">{{ $invoice->amount }} {{ __('messages.EGP') }}</td>
                            <td class="px-4 py-2">{{ $invoice->is_paid ? __('messages.Yes') : __('messages.No') }}</td>
                            <td class="px-4 py-2">{{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-2">{{ $invoice->user?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-gray-500">{{ __('messages.No invoices found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $invoices->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
