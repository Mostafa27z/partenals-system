<x-app-layout> 
    <x-slot name="header"> 
        <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">إدارة الأنظمة</h2> 
        <a href="{{ route('plans.trashed') }}" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700">
    🗑️ الانظمة المحذوفة
</a>
        </div>
    </x-slot> 
 
    <div class="py-4 px-6"> 
        <form method="GET" class="mb-4 flex flex-wrap items-center gap-2 justify-between" dir="rtl"> 
            <div class="flex gap-2">
                <input name="search" placeholder="بحث..." class="border px-2 py-1 rounded" /> 
                <button class="bg-blue-500  px-3 py-1 rounded">بحث</button> 
                <a href="{{ route('plans.export') }}" class="bg-green-500  px-3 py-1 rounded">تصدير Excel</a> 
            </div>
            <a href="{{ route('plans.create') }}" class="bg-purple-600  px-4 py-1.5 rounded">+ إضافة نظام</a> 
        </form> 
 
        <table class="w-full border text-right" dir="rtl"> 
            <thead class="bg-gray-100"> 
                <tr> 
                    <th class="border p-2">الاسم</th> 
                    <th class="border p-2">السعر</th> 
                    <th class="border p-2">مشغل الخدمة</th> 
                    <th class="border p-2">سعر المشغل</th> 
                    <th class="border p-2">النوع</th> 
                    <th class="border p-2">ID</th> 
                    <th class="border p-2">وصف النظام</th> 
                    <th class="border p-2">التحكم</th> 
                </tr> 
            </thead> 
            <tbody> 
                @foreach($plans as $plan) 
                    <tr> 
                        <td class="border p-2">{{ $plan->name }}</td> 
                        <td class="border p-2">{{ $plan->price }}</td> 
                        <td class="border p-2">{{ $plan->provider }}</td> 
                        <td class="border p-2">{{ $plan->provider_price }}</td> 
                        <td class="border p-2">{{ $plan->type }}</td> 
                        <td class="border p-2">{{ $plan->plan_code }}</td> 
                        <td class="border p-2">{{ $plan->penalty }}</td> 
                        <td class="border p-2"> 
                            <a href="{{ route('plans.show', $plan->id) }}" class="text-green-600 hover:underline">عرض</a>
                            <a href="{{ route('plans.edit', $plan->id) }}" class="text-blue-500">تعديل</a> 
                            <form method="POST" action="{{ route('plans.destroy', $plan->id) }}" class="inline"> 
                                @csrf 
                                @method('DELETE') 
                                <button onclick="return confirm('هل أنت متأكد؟')" class="text-red-500 ml-2">حذف</button> 
                            </form> 
                        </td> 
                    </tr> 
                @endforeach 
            </tbody> 
        </table> 
 
        <div class="mt-4">{{ $plans->links() }}</div> 
    </div> 
</x-app-layout>
