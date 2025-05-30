<!-- resources/views/admin/customers/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            إضافة عميل جديد
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            <div>
                <label class="block font-medium">الاسم الكامل</label>
                <input type="text" name="full_name" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div>
                <label class="block font-medium">رقم الهاتف</label>
                <input type="text" name="phone_number" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div>
                <label class="block font-medium">الرقم القومي</label>
                <input type="text" name="national_id" class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <label class="block font-medium">مزود الخدمة</label>
                <input type="text" name="provider" class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div class="flex justify-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    حفظ
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
