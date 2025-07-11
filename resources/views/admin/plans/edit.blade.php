<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('messages.Edit Plan') }}</h2>
    </x-slot>

    <div class="py-6 px-4 max-w-4xl mx-auto" dir="rtl">
        <form method="POST" action="{{ route('plans.update', $plan->id) }}" class="space-y-6 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.Plan Name') }}</label>
                <input type="text" name="name" value="{{ old('name', $plan->name) }}" class="w-full border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.Price') }} ({{ __('messages.EGP') }})</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}" class="w-full border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.Provider') }}</label>
                <select name="provider" class="w-full border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $code)
                        <option value="{{ $code }}" {{ old('provider', $plan->provider) === $code ? 'selected' : '' }}>
                            {{ $code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.Provider Price') }} ({{ __('messages.EGP') }})</label>
                <input type="number" step="0.01" name="provider_price" value="{{ old('provider_price', $plan->provider_price) }}" class="w-full border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.Type') }}</label>
                <input type="text" name="type" value="{{ old('type', $plan->type) }}" class="w-full border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.Plan Code') }}</label>
                <input type="text" name="plan_code" value="{{ old('plan_code', $plan->plan_code) }}" class="w-full border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">{{ __('messages.Description / Penalty') }}</label>
                <input type="text" name="penalty" value="{{ old('penalty', $plan->penalty) }}" class="w-full border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="text-left">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    {{ __('messages.Update') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
