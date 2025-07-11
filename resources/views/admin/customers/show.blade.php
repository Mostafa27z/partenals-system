<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تفاصيل العميل</h2> 
    </x-slot> 
 
    <div class="py-6" dir="rtl"> 
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> 
            <div class="bg-white p-6 rounded shadow"> 
                <ul class="space-y-2"> 
                    <li><strong>ID:</strong> {{ $customer->id }}</li>
                    <li><strong>الاسم:</strong> {{ $customer->full_name }}</li> 
                    <li><strong>الرقم القومي:</strong> {{ $customer->national_id }}</li> 
                    <li><strong>تاريخ الميلاد:</strong> {{ $customer->birth_date }}</li> 
                    <li><strong>البريد الإلكتروني:</strong> {{ $customer->email }}</li> 
                    <li><strong>العنوان:</strong> {{ $customer->address }}</li> 
                    <li><strong>تاريخ الإضافة:</strong> {{ $customer->created_at }}</li> 
                    <li><strong>آخر تعديل:</strong> {{ $customer->updated_at }}</li> 
                    <li><strong>تاريخ الحذف (إن وجد):</strong> 
                        {{ $customer->deleted_at ?? 'غير محذوف' }}
                    </li> 
 
                    <li><strong>الأرقام المرتبطة:</strong> 
                        <ul>  
                            @foreach($customer->lines as $line)  
                                <li> 
                                    {{ $line->phone_number }} - {{ $line->provider }} ({{ $line->line_type }}) 
                                    <a href="{{ route('lines.show', $line) }}" class="text-blue-600 hover:underline ml-2">عرض</a> 
                                </li>  
                            @endforeach  
                        </ul>  
                    </li> 
                </ul> 
            </div> 
        </div> 
    </div> 
</x-app-layout>
