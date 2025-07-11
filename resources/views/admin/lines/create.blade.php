<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 leading-tight">{{ __('messages.add_new_line') }}</h2>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow max-w-4xl mx-auto">
            {{ session('success') ?? __('messages.success_message') }}
        </div>
    @endif

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" dir="rtl">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded max-w-4xl mx-auto">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('lines.store') }}" method="POST" class="space-y-6 bg-white p-6 rounded shadow max-w-4xl mx-auto">
            @csrf

            <div>
                <label class="block mb-1 font-medium text-gray-700">مقدمة الرقم (GCode)</label>
                <select name="gcode" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300" required>
                    @foreach(['010', '011', '012', '015'] as $code)
                        <option value="{{ $code }}" {{ old('gcode') == $code ? 'selected' : '' }}>{{ $code }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">رقم الهاتف</label>
                <input type="text" name="phone_number" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300" value="{{ old('phone_number') }}" required>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">الموزع</label>
                <input type="text" name="distributor" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300" value="{{ old('distributor', $line->distributor ?? '') }}">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">مزود الخدمة</label>
                <select name="provider" id="provider-select" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300" required onchange="filterPlans()">
                    <option value="">{{ __('messages.select_provider') }}</option>
                    @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $provider)
                        <option value="{{ $provider }}" {{ old('provider') == $provider ? 'selected' : '' }}>
                            {{ $provider }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">نوع الخط</label>
                <select name="line_type" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300" required>
                    <option value="prepaid" {{ old('line_type') == 'prepaid' ? 'selected' : '' }}>مدفوع مسبقاً</option>
                    <option value="postpaid" {{ old('line_type') == 'postpaid' ? 'selected' : '' }}>فاتورة</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">النظام</label>
                <select name="plan_id" id="plan-select" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300">
                    <option value="">{{ __('messages.select_plan') }}</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" data-provider="{{ $plan->provider }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} ({{ $plan->provider }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">الباقة</label>
                <input type="text" name="package" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300" value="{{ old('package') }}">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">تاريخ الدفع</label>
                <input type="date" name="last_invoice_date" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300" value="{{ old('last_invoice_date') }}">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">ملاحظات</label>
                <textarea name="notes" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300" rows="3">{{ old('notes') }}</textarea>
            </div>

            <hr class="my-6 border-gray-300">

            <div>
                <label class="block mb-1 font-medium text-gray-700">الرقم القومي</label>
                <input type="text" id="search-nid" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300" placeholder="أدخل الرقم القومي" />
                <button type="button" onclick="loadCustomerData()" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm transition">
                    {{ __('messages.load_data') }}
                </button>
            </div>

            <div id="customer-data-fields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 hidden">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">اسم العميل</label>
                    <input type="text" name="full_name" id="full_name" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300">
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300">
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">تاريخ الميلاد</label>
                    <input type="date" name="birth_date" id="birth_date" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300">
                </div>

                <div>
                    <label class="block mb-1 font-medium text-gray-700">العنوان</label>
                    <input type="text" name="address" id="address" class="input input-bordered w-full rounded border-gray-300 focus:ring focus:ring-blue-300">
                </div>

                <input type="hidden" name="existing_customer_id" id="existing_customer_id" />

                <div class="col-span-1 sm:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="update_customer_data" class="form-checkbox h-5 w-5 text-blue-600">
                        {{ __('messages.update_customer_data') }}
                    </label>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                <button type="submit" name="save_and_add_more" value="1" class="btn btn-secondary w-full sm:w-auto">
                    💾 {{ __('messages.save_and_add_more') }}
                </button>
                <button type="submit" class="btn btn-primary w-full sm:w-auto">
                    ➕ {{ __('messages.add_line') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function loadCustomerData() {
            let nid = document.getElementById('search-nid').value.trim();
            if (!nid || nid.length !== 14) {
                alert('{{ __("messages.enter_valid_nid") }}');
                return;
            }
            fetch(`/admin/ajax/customer-by-nid?q=${nid}`)
                .then(res => res.json())
                .then(data => {
                    if (data) {
                        document.getElementById('full_name').value = data.full_name || '';
                        document.getElementById('email').value = data.email || '';
                        document.getElementById('birth_date').value = data.birth_date || '';
                        document.getElementById('address').value = data.address || '';
                        document.getElementById('existing_customer_id').value = data.id;
                        document.getElementById('customer-data-fields').classList.remove('hidden');
                    } else {
                        alert('{{ __("messages.customer_not_found") }}');
                    }
                })
                .catch(() => alert('{{ __("messages.error_occurred") }}'));
        }

        function filterPlans() {
            const selectedProvider = document.getElementById('provider-select').value;
            const planSelect = document.getElementById('plan-select');
            const options = planSelect.options;

            for (let i = 0; i < options.length; i++) {
                const opt = options[i];
                const planProvider = opt.getAttribute('data-provider');

                if (!planProvider || planProvider === selectedProvider || opt.value === '') {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            }

            planSelect.value = '';
        }
    </script>
    @endpush
</x-app-layout>
