<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">تفاصيل النظام</h2>
    </x-slot>

    <div class="py-4 px-6 space-y-2 text-right" dir="rtl">
        <p><strong>الاسم:</strong> {{ $plan->name }}</p>
        <p><strong>السعر:</strong> {{ $plan->price }}</p>
        <p><strong>مشغل الخدمة:</strong> {{ $plan->provider }}</p>
        <p><strong>سعر المشغل:</strong> {{ $plan->provider_price }}</p>
        <p><strong>النوع:</strong> {{ $plan->type }}</p>
        <p><strong>ID:</strong> {{ $plan->plan_code }}</p>
        <p><strong>وصف النظام:</strong> {{ $plan->penalty }}</p>

        <a href="{{ route('plans.edit', $plan->id) }}" class="text-blue-500">تعديل</a>
    </div>
</x-app-layout>
