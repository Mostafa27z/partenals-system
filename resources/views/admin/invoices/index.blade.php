<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('messages.All Invoices') }}
        </h2>
        <div class="mt-2 text-right text-green-700 font-bold text-lg">
            💰 {{ __('messages.Total') }}: {{ number_format($total, 2) }} {{ __('messages.EGP') }}
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-white p-6 rounded shadow">
        {{-- Provider --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('messages.Provider') }}</label>
            <select name="provider[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $p)
                    <option value="{{ $p }}" {{ in_array($p, request('provider', [])) ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>

        {{-- Line Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('messages.Line Type') }}</label>
            <select name="line_type[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="prepaid" {{ in_array('prepaid', request('line_type', [])) ? 'selected' : '' }}>{{ __('messages.Prepaid') }}</option>
                <option value="postpaid" {{ in_array('postpaid', request('line_type', [])) ? 'selected' : '' }}>{{ __('messages.Postpaid') }}</option>
            </select>
        </div>

        {{-- Plan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('messages.Plan') }}</label>
            <select name="plan_id[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ in_array($plan->id, request('plan_id', [])) ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Payment Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('messages.Payment Status') }}</label>
            <select name="is_paid[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="1" {{ in_array('1', request('is_paid', [])) ? 'selected' : '' }}>{{ __('messages.Paid') }}</option>
                <option value="0" {{ in_array('0', request('is_paid', [])) ? 'selected' : '' }}>{{ __('messages.Unpaid') }}</option>
            </select>
        </div>

        {{-- Date From --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('messages.From Date') }}</label>
            <input type="date" name="from" value="{{ request('from') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Date To --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('messages.To Date') }}</label>
            <input type="date" name="to" value="{{ request('to') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="md:col-span-3 flex justify-end mt-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                🔍 {{ __('messages.Filter') }}
            </button>
        </div>
    </form>

    {{-- Invoices Table --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto bg-white p-6 rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm text-center divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">{{ __('messages.Customer') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Month') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Amount') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Paid') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Payment Date') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Paid By') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $invoice->customer->full_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($invoice->invoice_month)->translatedFormat('F Y') }}</td>
                            <td class="px-4 py-2">{{ $invoice->amount }} {{ __('messages.EGP') }}</td>
                            <td class="px-4 py-2">{{ $invoice->is_paid ? __('messages.Yes') : __('messages.No') }}</td>
                            <td class="px-4 py-2">{{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-2">{{ $invoice->user?->name ?? '-' }}</td>
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
