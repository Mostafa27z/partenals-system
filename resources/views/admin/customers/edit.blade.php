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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تعديل بيانات العميل</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>الاسم بالكامل</label>
                            <input type="text" name="full_name" class="input input-bordered w-full" value="{{ $customer->full_name }}">
                        </div>

                        <div>
                            <label>الحالة</label>
                            <input type="text" name="status" class="input input-bordered w-full" value="{{ $customer->status }}">
                        </div>

                        <div>
                            <label>اسم العرض</label>
                            <input type="text" name="offer_name" class="input input-bordered w-full" value="{{ $customer->offer_name }}">
                        </div>

                        <div>
                            <label>اسم الفرع</label>
                            <input type="text" name="branch_name" class="input input-bordered w-full" value="{{ $customer->branch_name }}">
                        </div>

                        <div>
                            <label>اسم الموظف</label>
                            <input type="text" name="employee_name" class="input input-bordered w-full" value="{{ $customer->employee_name }}">
                        </div>

                        <div>
                            <label>GCode</label>
                            <input type="text" name="gcode" class="input input-bordered w-full" value="{{ $customer->gcode }}">
                        </div>

                        <div>
                            <label>رقم الهاتف</label>
                            <input type="text" name="phone_number" class="input input-bordered w-full" value="{{ $customer->phone_number }}">
                        </div>

                        <div>
                            <label>المزود</label>
                            <input type="text" name="provider" class="input input-bordered w-full" value="{{ $customer->provider }}">
                        </div>

                        <div>
                            <label>الرقم القومي</label>
                            <input type="text" name="national_id" class="input input-bordered w-full" value="{{ $customer->national_id }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>