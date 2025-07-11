<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800">تفاصيل الخط</h2> 
    </x-slot> 
 
    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8" dir="rtl"> 
        <div class="bg-white p-6 rounded shadow space-y-4"> 
            <ul class="space-y-2"> 
                <li><strong>ID:</strong> {{ $line->id }}</li> 
                <li><strong>رقم العميل:</strong> {{ $line->customer_id ?? '-' }}</li> 
                <li><strong>تاريخ الربط:</strong> {{ $line->attached_at ?? '-' }}</li> 
                <li><strong>رقم الهاتف:</strong> {{ $line->phone_number }}</li> 
                <li><strong>رقم إضافي:</strong> {{ $line->second_phone ?? '-' }}</li> 
                <li><strong>المزود:</strong> {{ $line->provider }}</li> 
                <li><strong>الحالة:</strong> {{ $line->status ?? '-' }}</li> 
                <li><strong>اسم العرض:</strong> {{ $line->offer_name ?? '-' }}</li> 
                <li><strong>اسم الفرع:</strong> {{ $line->branch_name ?? '-' }}</li> 
                <li><strong>اسم الموظف:</strong> {{ $line->employee_name ?? '-' }}</li> 
                <li><strong>المقدمة:</strong> {{ $line->gcode ?? '-' }}</li> 
                <li><strong>الموزّع:</strong> {{ $line->distributor ?? '-' }}</li> 
                <li><strong>نوع الخط:</strong> {{ $line->line_type === 'prepaid' ? 'مدفوع مسبقاً' : 'فاتورة' }}</li> 
                <li><strong>النظام:</strong> {{ $line->plan->name ?? 'غير محدد' }}</li> 
                <li><strong>الباقة:</strong> {{ $line->package ?? '-' }}</li> 
                {{-- <li><strong>تاريخ الدفع:</strong> {{ $line->payment_date ?? '-' }}</li>  --}}
                <li><strong>تاريخ آخر فاتورة:</strong> {{ $line->last_invoice_date ?? '-' }}</li> 
                <li><strong>ملاحظات:</strong> {{ $line->notes ?? '-' }}</li> 
                <li><strong>أضيف بواسطة:</strong> {{ $line->addedBy->name ?? 'غير معروف' }}</li>  
                <li><strong>تاريخ الإنشاء:</strong> {{ $line->created_at }}</li> 
                <li><strong>آخر تعديل:</strong> {{ $line->updated_at }}</li> 
                <li><strong>للبيع؟</strong> {{ $line->for_sale ? 'نعم' : 'لا' }}</li> 
                <li><strong>سعر البيع:</strong> {{ $line->sale_price ?? '-' }}</li> 
                <li><strong>تاريخ الحذف:</strong> {{ $line->deleted_at ?? 'غير محذوف' }}</li> 
            </ul> 

            @if($line->customer) 
                <div class="pt-4"> 
                    <a href="{{ route('customers.show', $line->customer) }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow inline-block"> 
                        🔙 رجوع إلى تفاصيل العميل 
                    </a> 
                </div> 
            @endif 
        </div> 
    </div> 
</x-app-layout>
