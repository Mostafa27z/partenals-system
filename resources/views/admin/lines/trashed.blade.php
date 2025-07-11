<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">🗑️ الخطوط المحذوفة مؤقتًا</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" dir="rtl">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-4 overflow-x-auto">
            <table class="min-w-full text-center divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th>رقم الهاتف</th>
                        <th>العميل</th>
                        <th>الرقم القومي</th>
                        <th>المزود</th>
                        <th>تاريخ الحذف</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lines as $line)
                        <tr>
                            <td>{{ $line->phone_number }}</td>
                            <td>{{ $line->customer->full_name ?? '-' }}</td>
                            <td>{{ $line->customer->national_id ?? '-' }}</td>
                            <td>{{ $line->provider }}</td>
                            <td>{{ $line->deleted_at->format('Y-m-d H:i') }}</td>
                            <td class="flex justify-center gap-2 flex-wrap">
                                <form action="{{ route('lines.restore', $line->id) }}" method="POST">
                                    @csrf
                                    <button class="text-green-600 hover:underline" onclick="return confirm('هل تريد استرجاع الخط؟')">
                                        ♻️ استرجاع
                                    </button>
                                </form>

                                <form action="{{ route('lines.forceDelete', $line->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline" onclick="return confirm('❌ هل أنت متأكد من الحذف النهائي؟')">
                                        🗑️ حذف نهائي
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-gray-500 py-4">لا يوجد خطوط محذوفة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $lines->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
