<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">إضافة خط جديد</h2>
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

        <form action="{{ route('lines.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf

            <div>
                <label class="block font-medium">مقدمة الرقم (GCode)</label>
                <select name="gcode" class="input input-bordered w-full" required>
                    @foreach(['010', '011', '012', '015'] as $code)
                        <option value="{{ $code }}" {{ old('gcode') == $code ? 'selected' : '' }}>{{ $code }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium">رقم الهاتف </label>
                <input type="text" name="phone_number" class="input input-bordered w-full"
                       value="{{ old('phone_number') }}" required>
            </div>

            <div>
                <label class="block font-medium">مزود الخدمة</label>
                <select name="provider" class="input input-bordered w-full" required>
                    @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $provider)
                        <option value="{{ $provider }}" {{ old('provider') == $provider ? 'selected' : '' }}>
                            {{ $provider }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium">نوع الخط</label>
                <select name="line_type" class="input input-bordered w-full" required>
                    <option value="prepaid" {{ old('line_type') == 'prepaid' ? 'selected' : '' }}>مدفوع مسبقاً</option>
                    <option value="postpaid" {{ old('line_type') == 'postpaid' ? 'selected' : '' }}>فاتورة</option>
                </select>
            </div>

            <div>
                <label class="block font-medium">النظام</label>
                <select name="plan_id" class="input input-bordered w-full">
                    <option value="">-- اختر نظاماً --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium">الباقة</label>
                <input type="text" name="package" class="input input-bordered w-full" value="{{ old('package') }}">
            </div>

            <div>
                <label class="block font-medium">تاريخ الدفع</label>
                <input type="date" name="payment_date" class="input input-bordered w-full"
                       value="{{ old('payment_date') }}">
            </div>

            <div>
                <label class="block font-medium">ملاحظات</label>
                <textarea name="notes" class="input input-bordered w-full">{{ old('notes') }}</textarea>
            </div>

            <hr class="my-4">

            <div>
                <label class="block font-medium">ربط عميل موجود (اختياري)</label>
                <select name="customer_id" id="customer-select" class="input input-bordered w-full">
                    @if(old('customer_id'))
                        <option value="{{ old('customer_id') }}" selected>{{ old('customer_national_id') }} - {{ old('customer_name') }}</option>
                    @endif
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">اسم عميل جديد (اختياري)</label>
                    <input type="text" name="new_full_name" class="input input-bordered w-full" value="{{ old('new_full_name') }}">
                </div>

                <div>
                    <label class="block font-medium">الرقم القومي</label>
                    <input type="text" name="new_national_id" class="input input-bordered w-full" value="{{ old('new_national_id') }}">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">➕ إضافة الخط</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#customer-select').select2({
                    placeholder: 'ابحث برقم قومي...',
                    minimumInputLength: 3,
                    ajax: {
                        url: '{{ route("ajax.customers.search") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return { q: params.term };
                        },
                        processResults: function (data) {
                            return {
                                results: data.map(customer => ({
                                    id: customer.id,
                                    text: customer.national_id + ' - ' + customer.full_name
                                }))
                            };
                        },
                        cache: true
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
