<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">⛔ طلب إيقاف نهائي للخط</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-6 bg-white p-6 rounded shadow space-y-6">

        <div class="bg-gray-100 p-4 rounded border">
            <h3 class="font-semibold mb-2">📱 بيانات الخط</h3>
            <div><strong>رقم الهاتف:</strong> {{ $line->phone_number }}</div>
            <div><strong>العميل:</strong> {{ $line->customer?->full_name ?? '-' }}</div>
            <div><strong>الرقم القومي:</strong> {{ $line->customer?->national_id ?? '-' }}</div>
            <div><strong>النظام:</strong> {{ $line->plan?->name ?? '-' }}</div>
        </div>

        <form method="POST" action="{{ route('requests.stop.store') }}">
            @csrf

            <input type="hidden" name="line_id" value="{{ $line->id }}">
            <input type="hidden" name="customer_id" value="{{ $line->customer_id }}">

            <div>
                <label class="block font-medium mb-1">📄 سبب الإيقاف النهائي</label>
                <input type="text" name="reason" class="input input-bordered w-full" required placeholder="مثلاً: رغبة العميل - انتهاء الاستخدام">
            </div>

            <div>
                <label class="block font-medium mb-1">📝 ملاحظات إضافية</label>
                <textarea name="comment" rows="3" class="input input-bordered w-full" placeholder="ملاحظات إن وجدت..."></textarea>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    ⛔ تأكيد إيقاف الخط
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
