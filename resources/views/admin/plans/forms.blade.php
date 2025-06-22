@php $plan = $plan ?? null; @endphp

<div class="grid grid-cols-1 gap-4">
    <div>
        <label>الاسم:</label>
        <input type="text" name="name" class="w-full border p-2 rounded" value="{{ old('name', $plan->name ?? '') }}">
    </div>
    <div>
        <label>السعر:</label>
        <input type="number" name="price" step="0.01" class="w-full border p-2 rounded" value="{{ old('price', $plan->price ?? '') }}">
    </div>
    <div>
        <label>مشغل الخدمة:</label>
        <input type="text" name="provider" class="w-full border p-2 rounded" value="{{ old('provider', $plan->provider ?? '') }}">
    </div>
    <div>
        <label>سعر المشغل:</label>
        <input type="number" name="provider_price" step="0.01" class="w-full border p-2 rounded" value="{{ old('provider_price', $plan->provider_price ?? '') }}">
    </div>
    <div>
        <label>النوع:</label>
        <input type="text" name="type" class="w-full border p-2 rounded" value="{{ old('type', $plan->type ?? '') }}">
    </div>
    <div>
        <label>ID:</label>
        <input type="text" name="identifier" class="w-full border p-2 rounded" value="{{ old('identifier', $plan->identifier ?? '') }}">
    </div>
    <div>
        <label>وصف النظام:</label>
        <input type="number" name="penalty" step="0.01" class="w-full border p-2 rounded" value="{{ old('penalty', $plan->penalty ?? '') }}">
    </div>
</div>
