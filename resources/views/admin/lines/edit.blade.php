<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">{{ __('messages.edit_line') }}</h2>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('lines.update', $line) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">{{ __('messages.gcode') }}</label>
                    <select name="gcode" class="input input-bordered w-full" required>
                        @foreach(['010', '011', '012', '015'] as $code)
                            <option value="{{ $code }}" {{ old('gcode', $line->gcode) == $code ? 'selected' : '' }}>{{ $code }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium">{{ __('messages.phone_number') }}</label>
                    <input type="text" value="{{ old('phone_number', $line->phone_number) }}" class="input input-bordered w-full" disabled>
                    <input type="hidden" name="phone_number" value="{{ old('phone_number', $line->phone_number) }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">{{ __('messages.distributor') }}</label>
                    <input type="text" name="distributor" class="input input-bordered w-full" value="{{ old('distributor', $line->distributor) }}">
                </div>

                <div>
                    <label class="block font-medium">{{ __('messages.provider') }}</label>
                    <select name="provider" class="input input-bordered w-full" required>
                        @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $provider)
                            <option value="{{ $provider }}" {{ old('provider', $line->provider) == $provider ? 'selected' : '' }}>{{ $provider }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">{{ __('messages.line_type') }}</label>
                    <select name="line_type" class="input input-bordered w-full" required>
                        <option value="prepaid" {{ old('line_type', $line->line_type) == 'prepaid' ? 'selected' : '' }}>مدفوع مسبقاً</option>
                        <option value="postpaid" {{ old('line_type', $line->line_type) == 'postpaid' ? 'selected' : '' }}>فاتورة</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium">{{ __('messages.last_invoice_date') }}</label>
                    <input type="date" name="last_invoice_date" class="input input-bordered w-full"
                        value="{{ old('last_invoice_date', \Carbon\Carbon::parse($line->last_invoice_date)->format('Y-m-d')) }}">
                </div>
            </div>

            <div>
                <label class="block font-medium">{{ __('messages.plan') }}</label>
                <select name="plan_id" class="input input-bordered w-full">
                    <option value="">{{ __('messages.select_plan') }}</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $line->plan_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium">{{ __('messages.package') }}</label>
                <input type="text" name="package" class="input input-bordered w-full" value="{{ old('package', $line->package) }}">
            </div>

            {{-- الرقم القومي وتحميل بيانات العميل --}}
            <div>
                <label class="block font-medium">{{ __('messages.national_id') }}</label>
                <input type="text" name="national_id" id="search-nid" class="input input-bordered w-full"
                    value="{{ old('national_id', $line->customer?->national_id) }}"
                    placeholder="{{ __('messages.enter_national_id') }}" pattern="\d{14}">
                <button type="button" onclick="loadCustomerData()" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded">
                    {{ __('messages.load_data') }}
                </button>
            </div>

            <div id="customer-data-fields" class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block font-medium">{{ __('messages.customer_name') }}</label>
                    <input type="text" name="full_name" id="full_name" class="input input-bordered w-full"
                        value="{{ old('full_name', $line->customer?->full_name) }}">
                </div>

                <div>
                    <label class="block font-medium">{{ __('messages.email') }}</label>
                    <input type="email" name="email" id="email" class="input input-bordered w-full"
                        value="{{ old('email', $line->customer?->email) }}">
                </div>

                <div>
                    <label class="block font-medium">{{ __('messages.birth_date') }}</label>
                    <input type="date" name="birth_date" id="birth_date" class="input input-bordered w-full"
                        value="{{ old('birth_date', $line->customer?->birth_date) }}">
                </div>

                <div>
                    <label class="block font-medium">{{ __('messages.address') }}</label>
                    <input type="text" name="address" id="address" class="input input-bordered w-full"
                        value="{{ old('address', $line->customer?->address) }}">
                </div>

                <input type="hidden" name="existing_customer_id" id="existing_customer_id" value="{{ $line->customer_id }}" />

                <div class="col-span-2">
                    <label>
                        <input type="checkbox" id="update_customer_data" name="update_customer_data" checked>
                        {{ __('messages.update_customer_data') }}
                    </label>
                </div>
            </div>

            <div>
                <label class="block font-medium">{{ __('messages.notes') }}</label>
                <textarea name="notes" class="input input-bordered w-full">{{ old('notes', $line->notes) }}</textarea>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('lines.all') }}" class="btn btn-error">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn btn-primary">💾 {{ __('messages.save_changes') }}</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function loadCustomerData() {
            let nid = document.getElementById('search-nid').value.trim();

            if (!/^\d{14}$/.test(nid)) {
                alert('{{ __("messages.nid_invalid") }}');
                return;
            }

            let btn = document.querySelector('[onclick="loadCustomerData()"]');
            btn.innerHTML = '⏳ جاري التحميل...';
            btn.disabled = true;

            fetch(`/admin/ajax/customer-by-nid?q=${nid}`)
                .then(res => res.ok ? res.json() : Promise.reject('Network error'))
                .then(data => {
                    if (data.error) throw new Error(data.error);

                    document.getElementById('full_name').value = data.full_name || '';
                    document.getElementById('email').value = data.email || '';
                    document.getElementById('birth_date').value = data.birth_date || '';
                    document.getElementById('address').value = data.address || '';
                    document.getElementById('existing_customer_id').value = data.id;
                })
                .catch(err => {
                    alert('{{ __("messages.no_customer_or_error") }}' + err.message);
                    document.getElementById('existing_customer_id').value = '';
                })
                .finally(() => {
                    btn.innerHTML = '{{ __("messages.load_data") }}';
                    btn.disabled = false;
                });
        }
    </script>
    @endpush
</x-app-layout>
