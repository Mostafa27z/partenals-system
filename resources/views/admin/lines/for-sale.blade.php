<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">📦 إدارة الخطوط المعروضة للبيع</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto mt-6 bg-white p-6 rounded shadow">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('lines.mark-for-sale') }}">
            @csrf

            <table class="min-w-full text-center border divide-y divide-gray-200 mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th>📞 رقم الخط</th>
                        <th>👤 العميل</th>
                        <th>💰 السعر</th>
                        <th>📍 للبيع؟</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lines as $line)
                        <tr class="border-b">
                            <td>{{ $line->phone_number }}</td>
                            <td>{{ $line->customer?->full_name ?? '-' }}</td>

                            <!-- سعر البيع -->
                            <td>
                                <input type="number" step="0.01" name="lines[{{ $line->id }}][sale_price]"
                                       value="{{ old("lines.$line->id.sale_price", $line->for_sale ? $line->sale_price : '') }}"
                                       class="border p-1 rounded w-24 text-center">
                            </td>

                            <!-- حالة البيع -->
                            <td>
                                <input type="checkbox" name="lines[{{ $line->id }}][selected]" value="1"
                                       {{ $line->for_sale ? 'checked' : '' }}>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-end">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                    💾 حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
