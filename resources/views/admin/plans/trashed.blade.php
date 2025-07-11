<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                🗑️ الانظمة المحذوفة مؤقتًا
            </h2>

            <a href="{{ route('plans.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ⬅️ العودة للانظمة
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        @if($plans->count() > 0)
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-center">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">الاسم</th>
                            <th class="px-4 py-2">السعر</th>
                            <th class="px-4 py-2">المزود</th>
                            <th class="px-4 py-2">الكود</th>
                            <th class="px-4 py-2">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($plans as $plan)
                            <tr>
                                <td class="px-4 py-2">{{ $plan->name }}</td>
                                <td class="px-4 py-2">{{ $plan->price }}</td>
                                <td class="px-4 py-2">{{ $plan->provider ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $plan->plan_code ?? '-' }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <form action="{{ route('plans.restore', $plan->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:underline">
                                            🔄 استعادة
                                        </button>
                                    </form>

                                    <form action="{{ route('plans.force-delete', $plan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ هل أنت متأكد من حذف هذا النظام نهائيًا؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">
                                            🗑️ حذف نهائي
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $plans->links() }}
            </div>
        @else
            <div class="bg-white p-6 rounded shadow text-center text-gray-500">
                لا توجد انظمة محذوفة حاليًا.
            </div>
        @endif
    </div>
</x-app-layout>
