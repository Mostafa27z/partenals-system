<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">📝 سجل التعديلات</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6">
        <div class="bg-white p-6 rounded shadow">
            @if ($logs->count())
                <table class="min-w-full table-auto border border-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">📦 الموديل</th>
                            <th class="px-4 py-2 border">🔑 رقم السجل</th>
                            <th class="px-4 py-2 border">📝 الحقل</th>
                            <th class="px-4 py-2 border">القيمة القديمة</th>
                            <th class="px-4 py-2 border">القيمة الجديدة</th>
                            <th class="px-4 py-2 border">👤 المستخدم</th>
                            <th class="px-4 py-2 border">📅 التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($logs as $log)
    <tr> 
        <td class="px-4 py-2 border">
            @if (class_basename($log->model_type) === 'Customer')
                عميل
            @elseif (class_basename($log->model_type) === 'Line')
                خط
            @else
                {{ class_basename($log->model_type) }}
            @endif
        </td> 

        <td class="px-4 py-2 border">
            @if (class_basename($log->model_type) === 'Customer' && $log->model)
                {{ $log->model->national_id ?? '---' }}
            @elseif (class_basename($log->model_type) === 'Line' && $log->model)
                {{ $log->model->phone_number ?? '---' }}
            @else
                {{ $log->model_id }}
            @endif
        </td>

        <td class="px-4 py-2 border">{{ $log->field_name }}</td> 
        <td class="px-4 py-2 border text-red-700">{{ $log->old_value }}</td> 
        <td class="px-4 py-2 border text-green-700">{{ $log->new_value }}</td> 
        <td class="px-4 py-2 border">{{ $log->user?->name ?? 'System' }}</td> 
        <td class="px-4 py-2 border">{{ $log->created_at->format('Y-m-d H:i') }}</td> 
    </tr> 
@endforeach

                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            @else
                <p class="text-gray-600">لا يوجد تغييرات حالياً.</p>
            @endif
        </div>
    </div>
</x-app-layout>
