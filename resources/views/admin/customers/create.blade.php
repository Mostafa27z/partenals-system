<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('messages.Add New Customer') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded shadow">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6 bg-white p-6 rounded shadow-md">
            @csrf

            <!-- Full Name -->
            <div>
                <label class="block font-medium text-gray-700">{{ __('messages.Full Name') }}</label>
                <input type="text" name="full_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- National ID -->
            <div>
                <label class="block font-medium text-gray-700">{{ __('messages.National ID') }}</label>
                <input type="text" name="national_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Birth Date -->
            <div>
                <label class="block font-medium text-gray-700">{{ __('messages.Birth Date') }}</label>
                <input type="date" name="birth_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Email -->
            <div>
                <label class="block font-medium text-gray-700">{{ __('messages.Email') }}</label>
                <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Address -->
            <div>
                <label class="block font-medium text-gray-700">{{ __('messages.Address') }}</label>
                <input type="text" name="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <hr class="my-4">

            <h3 class="font-bold text-lg text-gray-800">{{ __('messages.First Line Info') }}</h3>

            <!-- Phone Number -->
            <div>
                <label class="block font-medium text-gray-700">{{ __('messages.Phone Number') }}</label>
                <input type="text" name="phone_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Provider -->
<div>
    <label class="block font-medium text-gray-700">{{ __('messages.Provider') }}</label>
    <select name="provider" id="provider" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        <option value="">{{ __('messages.Choose Provider') }}</option>
        <option value="vodafone">Vodafone</option>
        <option value="orange">Orange</option>
        <option value="etisalat">Etisalat</option>
        <option value="we">WE</option>
    </select>
</div>


<!-- Plan -->
<div>
    <label class="block font-medium text-gray-700">{{ __('messages.Plan') }}</label>
    <select name="plan_id" id="plan_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        <option value="">{{ __('messages.Choose Plan') }}</option>
    </select>
</div>


            <!-- Line Type -->
            <div>
                <label class="block font-medium text-gray-700">{{ __('messages.Line Type') }}</label>
                <select name="line_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="prepaid">{{ __('messages.Prepaid') }}</option>
                    <option value="postpaid">{{ __('messages.Postpaid') }}</option>
                </select>
            </div>

            <!-- Plan -->
            {{-- <div>
                <label class="block font-medium text-gray-700">{{ __('messages.Plan') }}</label>
                <select name="plan_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('messages.Choose Plan') }}</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div> --}}

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    {{ __('messages.Save') }}
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const providerSelect = document.getElementById('provider');
        const planSelect = document.getElementById('plan_id');

        providerSelect.addEventListener('change', function () {
            const provider = this.value;

            if (!provider) return;

            fetch(`/ajax/plans/by-provider?q=${provider}`)
                .then(response => response.json())
                .then(data => {
                    planSelect.innerHTML = '<option value="">{{ __('messages.Choose Plan') }}</option>';
                    data.forEach(plan => {
                        const option = document.createElement('option');
                        option.value = plan.id;
                        option.text = plan.name;
                        planSelect.appendChild(option);
                    });
                })
                .catch(err => console.error('Error fetching plans:', err));
        });
    });
</script>
@endpush


</x-app-layout>
