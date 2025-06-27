<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">جميع الخطوط</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" dir="rtl">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 flex flex-wrap gap-4 items-center">
            <a href="{{ route('lines.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">➕ خط جديد</a>
            <a href="{{ route('lines.import.form') }}" class="btn btn-secondary">📥 رفع ملف Excel</a>

            <a href="{{ route('lines.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">⬇️ تصدير Excel</a>

            <form method="GET" action="{{ route('lines.all') }}" class="flex flex-wrap gap-4 mt-2 sm:mt-0">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="رقم الهاتف" class="input input-bordered w-full sm:w-40" />
                <input type="text" name="customer" value="{{ request('customer') }}" placeholder="اسم العميل" class="input input-bordered w-full sm:w-40" />
                <input type="text" name="provider" value="{{ request('provider') }}" placeholder="المزود" class="input input-bordered w-full sm:w-40" />
                <input type="text" name="distributor" value="{{ request('distributor') }}" placeholder="الموزع" class="input input-bordered w-full sm:w-40" />

                <button class="btn btn-primary">بحث</button>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-center" dir="rtl">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">رقم الهاتف</th>
                        <th class="px-4 py-2">العميل</th>
                        <th class="px-4 py-2">نوع الخط</th>
                        <th class="px-4 py-2">المزود</th>
                        <th class="px-4 py-2">النظام</th>
                        <th class="px-4 py-2">GCode</th>
                        <th class="px-4 py-2">الموزع</th>
                        <th class="px-4 py-2">تاريخ الربط</th>
                        <th class="px-4 py-2">الحالة</th>
                        <th class="px-4 py-2">آخر فاتورة</th>

                        <th class="px-4 py-2">الفواتير</th>
                        <th class="px-4 py-2">العمليات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($lines as $line)
                        <tr>
                            <td class="px-4 py-2">{{ $line->phone_number }}</td>
                            <td class="px-4 py-2">{{ $line->customer->full_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $line->line_type === 'prepaid' ? 'مدفوع مسبقاً' : 'فاتورة' }}</td>
                            <td class="px-4 py-2">{{ $line->provider }}</td>
                            <td class="px-4 py-2">{{ $line->plan->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $line->gcode }}</td>
                            <td class="px-4 py-2">{{ $line->distributor ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($line->attached_at)->format('Y-m-d') ?? '-' }}</td>

                            <td class="px-4 py-2">
                                {{ $line->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $line->last_invoice_date ? \Carbon\Carbon::parse($line->last_invoice_date)->format('Y-m-d') : '-' }}
                            </td>

                            <td class="px-4 py-2">
                                <a href="{{ route('lines.invoices', $line->id) }}" class="text-blue-500">عرض الفواتير</a>
                            </td>
                            <td class="px-4 py-2 space-x-2 flex justify-center gap-2 flex-wrap">
                            <a href="{{ route('lines.edit', $line->id) }}" class="text-yellow-500 hover:underline">تعديل</a>

                            <form action="{{ route('lines.destroy', $line->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('هل أنت متأكد من حذف الخط؟')">حذف</button>
                            </form>

                            @if($line->plan)
                                <a href="{{ route('invoices.create', $line) }}"
                                class="text-green-600 hover:underline">
                                    💳 دفع الفواتير
                                </a>
                            @endif
                        </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 px-4">
                {{ $lines->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
