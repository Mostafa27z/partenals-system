<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إدارة العملاء</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <form method="GET" action="{{ route('customers.index') }}" class="flex flex-wrap gap-4">
                    {{-- <input type="text" name="name" value="{{ request('name') }}" placeholder="الاسم" class="input input-bordered w-full sm:w-40" /> --}}
                    <input type="text" name="phone_number" value="{{ request('phone_number') }}" placeholder="رقم الهاتف" class="input input-bordered w-full sm:w-40" />
                    <input type="text" name="national_id" value="{{ request('national_id') }}" placeholder="الرقم القومي" class="input input-bordered w-full sm:w-40" />
                    {{-- <select name="status" class="input input-bordered w-full sm:w-40">
                        <option value="">-- الحالة --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select> --}}

                    <button class="btn btn-primary">بحث</button>
                    <a href="{{ route('customers.export') }}" class="btn btn-success">تصدير Excel</a>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                <table class="min-w-full divide-y divide-gray-200 text-center  " dir='rtl'>
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">الاسم</th>
                            {{-- <th class="px-4 py-2">الحالة</th>
                            <th class="px-4 py-2">العرض</th>
                            <th class="px-4 py-2">الفرع</th>
                            <th class="px-4 py-2">الموظف</th>
                            <th class="px-4 py-2">GCode</th>
                            <th class="px-4 py-2">الهاتف</th>
                            <th class="px-4 py-2">المزود</th> --}}
                            <th class="px-4 py-2">الرقم القومي</th>
                            <th class="px-4 py-2">الفواتير</th>
                            <th class="px-4 py-2 text-center" colspan='3'>العمليات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($customers as $customer)
                        <tr>
                            <td class="px-4 py-2">{{ $customer->full_name }}</td>
                            {{-- <td class="px-4 py-2">{{ $customer->status }}</td>
                            <td class="px-4 py-2">{{ $customer->offer_name }}</td>
                            <td class="px-4 py-2">{{ $customer->branch_name }}</td>
                            <td class="px-4 py-2">{{ $customer->employee_name }}</td>
                            <td class="px-4 py-2">{{ $customer->gcode }}</td>
                            <td class="px-4 py-2">{{ $customer->phone_number }}</td>
                            <td class="px-4 py-2">{{ $customer->provider }}</td> --}}
                            <td class="px-4 py-2">{{ $customer->national_id }}</td>
                            {{-- <td class="px-4 py-2">
                                <a href="{{ route('customers.invoices', $customer) }}" class="text-blue-500"> عرض الفواتير</a>
                            </td> --}}
                            <td><a href="{{ route('customers.invoices', $customer) }}" class="text-green-600 hover:underline">عرض الفواتير</a></td>
                            <td class="px-4 py-2">
                                <a href="{{ route('customers.show', $customer) }}" class="text-blue-500">عرض</a>
                            </td>
                            {{-- <td class="px-4 py-2">
                                <a href="{{ route('invoices.create', $customer) }}" class="text-blue-500">دفع فاتورة</a>
                            </td> --}}
                            <td class="px-4 py-2">
                                <a href="{{ route('customers.edit', $customer) }}" class="text-yellow-500">تعديل</a>
                                
                                
                            </td>
                            
                            <td class="px-4 py-2">
                               
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                </form>
                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
<div class="px-4 py-2 bg-green-500 text-black rounded "><a href="{{ route('customers.create') }}" class="px-4 py-2 bg-green-500 text-black rounded hover:bg-green-600">+ عميل جديد</a>
</div>
                        
        </div>
    </div>
</x-app-layout>