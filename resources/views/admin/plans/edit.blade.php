<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">تعديل النظام</h2>
    </x-slot>

    <div class="py-4 px-6" dir="rtl">
        <form method="POST" action="{{ route('plans.update', $plan->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium">الاسم</label>
                <input type="text" name="name" value="{{ $plan->name }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block font-medium">السعر</label>
                <input type="number" name="price" value="{{ $plan->price }}" step="0.01" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
    <label class="block font-medium">مشغل الخدمة (GCode)</label>
    <select name="provider" class="w-full border rounded px-3 py-2">
        @foreach(['010', '011', '012', '015'] as $code)
            <option value="{{ $code }}" {{ old('provider', $plan->provider) == $code ? 'selected' : '' }}>
                {{ $code }}
            </option>
        @endforeach
    </select>
</div>


            <div>
                <label class="block font-medium">سعر المشغل</label>
                <input type="number" name="provider_price" value="{{ $plan->provider_price }}" step="0.01" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-medium">النوع</label>
                <input type="text" name="type" value="{{ $plan->type }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-medium">ID</label>
                <input type="text" name="plan_code" value="{{ $plan->plan_code }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-medium">وصف النظام</label>
                <input type="text" name="penalty" value="{{ $plan->penalty }}" class="w-full border rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-blue-500  px-4 py-2 rounded">تحديث</button>
        </form>
    </div>
</x-app-layout>
