<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">اختر خط لإعادة البيع</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            @if($lines->count())
                <table class="min-w-full text-center border divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">رقم الهاتف</th>
                            <th class="px-4 py-2">العميل</th>
                            <th class="px-4 py-2">مزود الخدمة</th>
                            <th class="px-4 py-2">النظام</th>
                            <th class="px-4 py-2">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lines as $line)
                            <tr>
                                <td>{{ $line->phone_number }}</td>
                                <td>{{ $line->customer->full_name ?? '-' }}</td>
                                <td>{{ $line->provider }}</td>
                                <td>{{ $line->plan->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('requests.resell.create', $line->id) }}">
                                        🔁 طلب إعادة بيع
                                    </a>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $lines->links() }}
                </div>
            @else
                <p class="text-gray-500">لا توجد خطوط متاحة حالياً.</p>
            @endif
        </div>
    </div>
</x-app-layout>
