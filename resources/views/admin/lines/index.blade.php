<!-- resources/views/admin/lines/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            الخطوط التابعة للعميل: {{ $customer->full_name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('customers.lines.create', $customer) }}" class="btn btn-success">+ إضافة خط جديد</a>
        </div>

        <div class="bg-white p-6 rounded shadow">
            @if ($customer->lines->count())
                <table class="min-w-full divide-y divide-gray-200 text-center">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">رقم الهاتف</th>
                            <th class="px-4 py-2">نوع الخط</th>
                            <th class="px-4 py-2">المزود</th>
                            <th class="px-4 py-2">النظام</th>
                            <th class="px-4 py-2">تاريخ الدفع</th>
                            <th class="px-4 py-2">العمليات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customer->lines as $line)
                            <tr>
                                <td class="px-4 py-2">{{ $line->phone_number }}</td>
                                <td class="px-4 py-2">{{ $line->line_type == 'prepaid' ? 'مدفوع مسبقاً' : 'فاتورة' }}</td>
                                <td class="px-4 py-2">{{ $line->provider }}</td>
                                <td class="px-4 py-2">{{ $line->plan->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $line->payment_date }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('customers.lines.edit', [$customer, $line]) }}" class="text-blue-500">تعديل</a> |
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
                <p class="text-gray-500">لا توجد خطوط لهذا العميل حالياً.</p>
            @endif
        </div>
    </div>
</x-app-layout>
