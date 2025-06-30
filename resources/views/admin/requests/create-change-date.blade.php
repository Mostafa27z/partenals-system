<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">طلب تغيير التاريخ - {{ $line->phone_number }}</h2>
    </x-slot>

    <div class="max-w-xl mx-auto mt-6 bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('requests.change-date.store') }}">
            @csrf
            <input type="hidden" name="line_id" value="{{ $line->id }}">

            <div class="mb-4">
                <label class="block font-bold mb-1">التاريخ الحالي</label>
                <input type="text" class="w-full bg-gray-100 border p-2 rounded" disabled value="{{ $line->last_invoice_date }}">
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-1">التاريخ الجديد</label>
                <input type="date" name="new_date" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-1">السبب</label>
                <textarea name="reason" class="w-full border p-2 rounded" rows="3">{{ old('reason') }}</textarea>
            </div>

            <div class="text-end">
                <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">💾 حفظ الطلب</button>
            </div>
        </form>
    </div>
</x-app-layout>
