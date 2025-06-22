<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">تفاصيل الخط</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8" dir="rtl">
        <div class="bg-white p-6 rounded shadow space-y-4">
            <ul class="space-y-2">
                <li><strong>رقم الهاتف:</strong> {{ $line->phone_number }}</li>
                <li><strong>المزود:</strong> {{ $line->provider }}</li>
                <li><strong>نوع الخط:</strong> {{ $line->line_type === 'prepaid' ? 'مدفوع مسبقاً' : 'فاتورة' }}</li>
                <li><strong>النظام:</strong> {{ $line->plan->name ?? 'غير محدد' }}</li>
                <li><strong>الباقة:</strong> {{ $line->package ?? '-' }}</li>
                <li><strong>تاريخ الدفع:</strong> {{ $line->payment_date ?? '-' }}</li>
                <li><strong>ملاحظات:</strong> {{ $line->notes ?? '-' }}</li>
            </ul>

            @if($line->customer)
                <div class="pt-4">
                    <a href="{{ route('customers.show', $line->customer) }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow inline-block">
                        🔙 رجوع إلى تفاصيل العميل
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
