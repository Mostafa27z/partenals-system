@if(session('success'))
    <div class="mb-4 p-4 text-green-700 bg-green-100 rounded">
        {{ session('success') }}
    </div>
@elseif(session('error'))
    <div class="mb-4 p-4 text-red-700 bg-red-100 rounded">
        {{ session('error') }}
    </div>
@endif

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تفاصيل العميل</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <ul class="space-y-2">
                    <li><strong>الاسم:</strong> {{ $customer->full_name }}</li>
                    <li><strong>الحالة:</strong> {{ $customer->status }}</li>
                    <li><strong>العرض:</strong> {{ $customer->offer_name }}</li>
                    <li><strong>الفرع:</strong> {{ $customer->branch_name }}</li>
                    <li><strong>الموظف:</strong> {{ $customer->employee_name }}</li>
                    <li><strong>GCode:</strong> {{ $customer->gcode }}</li>
                    <li><strong>الهاتف:</strong> {{ $customer->phone_number }}</li>
                    <li><strong>المزود:</strong> {{ $customer->provider }}</li>
                    <li><strong>الرقم القومي:</strong> {{ $customer->national_id }}</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
