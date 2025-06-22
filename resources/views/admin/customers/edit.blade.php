<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تعديل بيانات العميل</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>الاسم بالكامل</label>
                            <input type="text" name="full_name" class="input input-bordered w-full" value="{{ old('full_name', $customer->full_name) }}">
                        </div>

                        <div>
                            <label>الرقم القومي</label>
                            <input type="text" name="national_id" class="input input-bordered w-full" value="{{ old('national_id', $customer->national_id) }}">
                        </div>

                        <div>
                            <label>تاريخ الميلاد</label>
                            <input type="date" name="birth_date" class="input input-bordered w-full" value="{{ old('birth_date', $customer->birth_date) }}">
                        </div>

                        <div>
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="input input-bordered w-full" value="{{ old('email', $customer->email) }}">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button class="btn btn-primary">تحديث</button>
                    </div>
                </form>
            </div>

            <!-- جدول عرض جميع الخطوط المرتبطة بالعميل -->
            <div class="bg-white mt-8 p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">الخطوط المرتبطة بالعميل</h3>
                @if($customer->lines->count())
                    <table class="min-w-full divide-y divide-gray-200 text-center">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2">رقم الهاتف</th>
                                <th class="px-4 py-2">نوع الخط</th>
                                <th class="px-4 py-2">المزود</th>
                                <th class="px-4 py-2">النظام</th>
                                <th class="px-4 py-2">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($customer->lines as $line)
                                <tr>
                                    <td class="px-4 py-2">{{ $line->phone_number }}</td>
                                    <td class="px-4 py-2">{{ $line->line_type == 'prepaid' ? 'مدفوع مسبقاً' : 'فاتورة' }}</td>
                                    <td class="px-4 py-2">{{ $line->provider }}</td>
                                    <td class="px-4 py-2">{{ $line->plan->name ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('customers.lines.edit', [$customer, $line]) }}" class="text-blue-500">تعديل</a>
                                        |
                                        <form action="{{ route('customers.lines.destroy', [$customer, $line]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('هل تريد حذف هذا الخط؟')" class="text-red-600">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-600">لا توجد خطوط مرتبطة بهذا العميل.</p>
                @endif

                <div class="mt-4">
                    <a href="{{ route('customers.lines.create', $customer) }}" class="btn btn-success">+ إضافة خط جديد</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
