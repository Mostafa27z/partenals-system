<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('messages.Plan Details') }}</h2>
    </x-slot>

    <div class="py-6 px-6 md:px-10 max-w-3xl mx-auto bg-white shadow rounded" dir="rtl">
        <div class="space-y-4 text-right text-gray-800 leading-relaxed">
            <p><strong>{{ __('messages.Name') }}:</strong> {{ $plan->name }}</p>
            <p><strong>{{ __('messages.Price') }}:</strong> {{ $plan->price }} {{ __('messages.EGP') }}</p>
            <p><strong>{{ __('messages.Provider') }}:</strong> {{ $plan->provider }}</p>
            <p><strong>{{ __('messages.Provider Price') }}:</strong> {{ $plan->provider_price }} {{ __('messages.EGP') }}</p>
            <p><strong>{{ __('messages.Type') }}:</strong> {{ $plan->type }}</p>
            <p><strong>{{ __('messages.Plan Code') }}:</strong> {{ $plan->plan_code }}</p>
            <p><strong>{{ __('messages.Description') }}:</strong> {{ $plan->penalty }}</p>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('plans.edit', $plan->id) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                {{ __('messages.Edit') }}
            </a>
        </div>
    </div>
</x-app-layout>
