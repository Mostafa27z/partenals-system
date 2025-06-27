<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">📄 تفاصيل طلب إعادة البيع</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white shadow p-6 rounded space-y-3">
            <p><strong>العميل:</strong> {{ $requestModel->line->customer->full_name ?? '-' }}</p>
            <p><strong>الرقم القومي:</strong> {{ $requestModel->line->customer->national_id ?? '-' }}</p>
            <p><strong>رقم الخط:</strong> {{ $requestModel->line->phone_number }}</p>
            <p><strong>نوع الطلب:</strong> {{ $requestModel->resellDetails->resell_type == 'chip' ? 'على الشريحة' : 'في الفرع' }}</p>
            <p><strong>المسلسل القديم:</strong> {{ $requestModel->resellDetails->old_serial ?? '-' }}</p>
            <p><strong>المسلسل الجديد:</strong> {{ $requestModel->resellDetails->new_serial ?? '-' }}</p>
            <p><strong>الاسم:</strong> {{ $requestModel->resellDetails->full_name ?? '-' }}</p>
            <p><strong>الرقم القومي:</strong> {{ $requestModel->resellDetails->national_id ?? '-' }}</p>
            <p><strong>تاريخ الطلب:</strong> {{ $requestModel->resellDetails->request_date }}</p>
            <p><strong>ملاحظات:</strong> {{ $requestModel->resellDetails->comment ?? '-' }}</p>
            <p><strong>الحالة:</strong> {{ $requestModel->status }}</p>
        </div>
    </div>
</x-app-layout>
