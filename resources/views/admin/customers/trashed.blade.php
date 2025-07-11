<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">🗑️ العملاء المحذوفين مؤقتاً</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded p-4">
            <table class="min-w-full table-auto text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th>الاسم</th>
                        <th>الرقم القومي</th>
                        <th>تاريخ الحذف</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->full_name }}</td>
                            <td>{{ $customer->national_id }}</td>
                            <td>{{ $customer->deleted_at->format('Y-m-d H:i') }}</td>
                            <td class="flex justify-center gap-4 mt-2">
                                <form action="{{ route('customers.restore', $customer->id) }}" method="POST">
                                    @csrf
                                    <button class="text-green-600 hover:underline">♻️ استرجاع</button>
                                </form>

                                <form action="{{ route('customers.forceDelete', $customer->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline" onclick="return confirm('❌ هل تريد حذف العميل نهائيًا؟')">🗑️ حذف نهائي</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-gray-500">لا يوجد عملاء محذوفين</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
