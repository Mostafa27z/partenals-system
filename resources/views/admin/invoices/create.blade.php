<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="text-xl font-semibold">💳 دفع فواتير - {{ $line->customer->full_name ?? 'غير مربوط بعميل' }}</h2> 
        {{-- <h2><div class="mt-4 text-right text-green-700 font-bold text-lg">
    💰 إجمالي الفواتير: {{ number_format($total, 2) }} ج.م
</div>
</h2> --}}
    </x-slot> 

    <form action="{{ route('invoices.store', $line) }}" method="POST" 
          class="max-w-lg mx-auto bg-white p-6 rounded shadow mt-6"> 
        @csrf 

        @php 
            $plan = $line->plan; 
            $monthlyPrice = $plan?->price ?? 0; 
        @endphp 

        <div class="mb-4"> 
            <label class="block font-bold mb-1">رقم الهاتف</label> 
            <input type="text" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" 
                   value="{{  $line->phone_number }}" disabled> 
        </div> 

        <div class="mb-4"> 
            <label class="block font-bold mb-1">نظام الخط</label> 
            <input type="text" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" 
                   value="{{ $plan?->name ?? 'لا يوجد نظام' }}" disabled> 
        </div> 

        <div class="mb-4"> 
            <label class="block font-bold mb-1">السعر الشهري</label> 
            <input type="text" id="monthly-price" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" 
                   value="{{ $monthlyPrice }}" disabled> 
        </div> 

        <div class="mb-4"> 
            <label class="block font-bold mb-1">عدد الأشهر المراد دفعها</label> 
            <input type="number" name="months_count" id="months-count" min="1" 
                   class="w-full border-gray-300 rounded px-3 py-2" required> 
        </div> 

        <div class="mb-4"> 
            <label class="block font-bold mb-1">💰 السعر الكلي</label> 
            <input type="text" id="total-price" class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" 
                   readonly> 
        </div> 

        <div class="mb-4">
            <label class="block font-bold mb-1">ملاحظات</label>
            <textarea name="notes" rows="3" class="w-full border-gray-300 rounded px-3 py-2">{{ old('notes') }}</textarea>
        </div>

        <div class="text-end"> 
            <button type="submit" 
                    class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">💵 دفع</button> 
        </div> 
    </form> 

    @push('scripts') 
        <script> 
            document.addEventListener('DOMContentLoaded', function () { 
                const monthlyPrice = parseFloat(document.getElementById('monthly-price').value) || 0; 
                const monthsInput = document.getElementById('months-count'); 
                const totalPrice = document.getElementById('total-price'); 

                monthsInput.addEventListener('input', function () { 
                    const months = parseInt(monthsInput.value) || 0; 
                    totalPrice.value = (months * monthlyPrice).toFixed(2); 
                }); 
            }); 
        </script> 
    @endpush 
</x-app-layout>
