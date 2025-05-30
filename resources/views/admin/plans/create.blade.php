<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">إضافة نظام جديد</h2>
    </x-slot>

    <div class="py-4 px-6 w-[80vw]" dir="rtl">
        <form method="POST" action="{{ route('plans.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium">الاسم</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block font-medium">السعر</label>
                <input type="number" name="price" step="0.01" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block font-medium">مشغل الخدمة</label>
                <input type="text" name="provider" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-medium">سعر المشغل</label>
                <input type="number" name="provider_price" step="0.01" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-medium">النوع</label>
                <input type="text" name="type" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-medium">ID</label>
                <input type="text" name="plan_code" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-medium">الغرامة</label>
                <input type="text" name="penalty" class="w-full border rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-blue-500 px-4 py-2 rounded">حفظ</button>
        </form>
    </div>
</x-app-layout>
