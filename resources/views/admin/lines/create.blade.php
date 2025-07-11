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
                <input type="text" name="phone_number" class="input input-bordered w-full" value="{{ old('phone_number') }}" required> 
            </div> 

            <div> 
                <label class="block font-medium">الموزع</label> 
                <input type="text" name="distributor" class="input input-bordered w-full" value="{{ old('distributor', $line->distributor ?? '') }}"> 
            </div> 

            <div> 
                <label class="block font-medium">مزود الخدمة</label> 
                <select name="provider" id="provider-select" class="input input-bordered w-full" required onchange="filterPlans()"> 
                    <option value="">-- اختر المزود --</option> 
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
                <select name="plan_id" id="plan-select" class="input input-bordered w-full"> 
                    <option value="">-- اختر نظاماً --</option> 
                    @foreach($plans as $plan) 
                        <option value="{{ $plan->id }}" 
                                data-provider="{{ $plan->provider }}" 
                                {{ old('plan_id') == $plan->id ? 'selected' : '' }}> 
                            {{ $plan->name }} ({{ $plan->provider }}) 
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
                <input type="date" name="last_invoice_date" class="input input-bordered w-full" value="{{ old('last_invoice_date') }}"> 
            </div> 

            <div> 
                <label class="block font-medium">ملاحظات</label> 
                <textarea name="notes" class="input input-bordered w-full">{{ old('notes') }}</textarea> 
            </div> 

            <hr class="my-4"> 

            <div> 
                <label class="block font-medium">الرقم القومي</label> 
                <input type="text" id="search-nid" class="input input-bordered w-full" placeholder="أدخل الرقم القومي" /> 
                <button type="button" onclick="loadCustomerData()" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded">🔍 تحميل البيانات</button> 
            </div> 

            <div id="customer-data-fields" class="grid grid-cols-2 gap-4 mt-4 hidden"> 
                <div> 
                    <label class="block font-medium">اسم العميل</label> 
                    <input type="text" name="full_name" id="full_name" class="input input-bordered w-full"> 
                </div> 

                <div> 
                    <label class="block font-medium">البريد الإلكتروني</label> 
                    <input type="email" name="email" id="email" class="input input-bordered w-full"> 
                </div> 

                <div> 
                    <label class="block font-medium">تاريخ الميلاد</label> 
                    <input type="date" name="birth_date" id="birth_date" class="input input-bordered w-full"> 
                </div> 

                <div> 
                    <label class="block font-medium">العنوان</label> 
                    <input type="text" name="address" id="address" class="input input-bordered w-full"> 
                </div> 

                <input type="hidden" name="existing_customer_id" id="existing_customer_id" /> 

                <div class="col-span-2"> 
                    <label><input type="checkbox" name="update_customer_data"> تحديث بيانات العميل إذا وُجد</label> 
                </div> 
            </div> 

            <div class="flex justify-end gap-2"> 
                <button type="submit" name="save_and_add_more" value="1" class="btn btn-secondary">💾 حفظ وإضافة مزيد</button> 
                <button type="submit" class="btn btn-primary">➕ إضافة الخط</button> 
            </div> 
        </form> 
    </div> 

    @push('scripts') 
        <script> 
            function loadCustomerData() { 
                let nid = document.getElementById('search-nid').value.trim(); 
                if (!nid || nid.length !== 14) { 
                    alert('❌ أدخل رقم قومي صحيح'); 
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
                            alert('❌ العميل غير موجود.'); 
                        } 
                    }) 
                    .catch(() => alert('حدث خطأ، تأكد من الاتصال')); 
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
