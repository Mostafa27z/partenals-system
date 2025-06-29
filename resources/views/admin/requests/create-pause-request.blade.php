<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">طلب إيقاف مؤقت - {{ $line->phone_number }}</h2>
    </x-slot>

    <div class="max-w-xl mx-auto bg-white p-6 mt-6 rounded shadow">
        <form method="POST" action="{{ route('requests.pause.store') }}">
            @csrf
            <input type="hidden" name="line_id" value="{{ $line->id }}">

            <div class="mb-4">
                <label class="block font-bold mb-1">السبب</label>
                <input type="text" name="reason" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-1">تعليق (اختياري)</label>
                <textarea name="comment" rows="3" class="w-full border p-2 rounded"></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">
                    💾 حفظ الطلب
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
