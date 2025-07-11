<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="text-xl font-semibold">💳 دفع فواتير - {{ $line->customer->full_name ?? 'غير مربوط بعميل' }}</h2> 
        {{-- <h2><div class="mt-4 text-right text-green-700 font-bold text-lg">
    💰 إجمالي الفواتير: {{ number_format($total, 2) }} ج.م
</div>
</h2> --}}
    </x-slot> 

   <form action="{{ route('invoices.store', $line) }}" method="POST"
    class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow mt-6 sm:px-8 sm:py-8">
    @csrf

    @php
        $plan = $line->plan;
        $monthlyPrice = $plan?->price ?? 0;
    @endphp

    <div class="mb-5">
        <label for="phone_number" class="block font-semibold mb-2 text-gray-700">{{ __('messages.Phone Number') }}</label>
        <input type="text" id="phone_number" disabled
            class="w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2"
            value="{{ $line->phone_number }}">
    </div>

    <div class="mb-5">
        <label for="plan_name" class="block font-semibold mb-2 text-gray-700">{{ __('messages.Line Plan') }}</label>
        <input type="text" id="plan_name" disabled
            class="w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2"
            value="{{ $plan?->name ?? __('messages.No Plan') }}">
    </div>

    <div class="mb-5">
        <label for="monthly-price" class="block font-semibold mb-2 text-gray-700">{{ __('messages.Monthly Price') }}</label>
        <input type="text" id="monthly-price" disabled
            class="w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2 font-mono"
            value="{{ $monthlyPrice }}">
    </div>

    <div class="mb-5">
        <label for="months-count" class="block font-semibold mb-2 text-gray-700">{{ __('messages.Months to Pay') }}</label>
        <input type="number" name="months_count" id="months-count" min="1" required
            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="mb-5">
        <label for="total-price" class="block font-semibold mb-2 text-gray-700">{{ __('messages.Total Price') }}</label>
        <input type="text" id="total-price" readonly
            class="w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2 font-mono cursor-not-allowed">
    </div>

    <div class="mb-5">
        <label for="period-range" class="block font-semibold mb-2 text-gray-700">{{ __('messages.Payment Period') }}</label>
        <input type="text" id="period-range" readonly
            class="w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2 cursor-not-allowed">
    </div>

    <div class="mb-5">
        <label for="notes" class="block font-semibold mb-2 text-gray-700">{{ __('messages.Notes') }}</label>
        <textarea name="notes" id="notes" rows="3"
            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
    </div>

    <div class="text-end">
        <button type="submit"
            class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
            {{ __('messages.Pay') }}
        </button>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlyPrice = parseFloat(document.getElementById('monthly-price').value) || 0;
            const monthsInput = document.getElementById('months-count');
            const totalPrice = document.getElementById('total-price');
            const periodRange = document.getElementById('period-range');

            monthsInput.addEventListener('input', function() {
                const months = parseInt(monthsInput.value) || 0;
                totalPrice.value = (months * monthlyPrice).toFixed(2);

                if (months > 0) {
                    const start = new Date();
                    const end = new Date(start);
                    end.setMonth(start.getMonth() + months - 1);

                    const options = {
                        year: 'numeric',
                        month: 'long'
                    };
                    const from = start.toLocaleDateString('ar-EG', options);
                    const to = end.toLocaleDateString('ar-EG', options);

                    periodRange.value = `من ${from} إلى ${to}`;
                } else {
                    periodRange.value = '';
                }
            });
        });
    </script>
@endpush


</x-app-layout>
