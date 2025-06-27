<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">طلب تغيير النظام لرقم {{ $line->phone_number }}</h2>
    </x-slot>

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow mt-6">
        <form method="POST" action="{{ route('requests.change-plan.store') }}">
            @csrf
            <input type="hidden" name="line_id" value="{{ $line->id }}">

            <div class="mb-4">
                <label class="block font-bold mb-1">النظام الجديد ({{ $line->provider }})</label>
                <select name="new_plan_id" class="w-full border p-2 rounded" required>
                    <option value="">-- اختر النظام --</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }} ({{ number_format($plan->price, 2) }} ج.م)</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-1">ملاحظات (اختياري)</label>
                <textarea name="comment" class="w-full border p-2 rounded">{{ old('comment') }}</textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">💾 حفظ الطلب</button>
            </div>
        </form>
    </div>
</x-app-layout>
