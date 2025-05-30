<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            معلومات الشركة
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('company.update', $company->id) }}" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-1 font-medium">اسم الشركة</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $company->company_name) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">وصف الشركة</label>
                        <input type="text" name="company_description" value="{{ old('company_description', $company->company_description) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">شعار الشركة</label>
                        <input type="file" name="company_logo" class="w-full border rounded p-2">
                        @if($company->company_logo)
                            <img src="{{ asset('storage/' . $company->company_logo) }}" alt="الشعار" class="w-20 mt-2">
                        @endif
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">البريد لتفعيل الحساب</label>
                        <input type="email" name="email_activation" value="{{ old('email_activation', $company->email_activation) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">اسم المستخدم لتفعيل الحساب</label>
                        <input type="text" name="username_activation" value="{{ old('username_activation', $company->username_activation) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">كلمة المرور للتفعيل</label>
                        <input type="text" name="password_activation" value="{{ old('password_activation', $company->password_activation) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">البورت للتفعيل</label>
                        <input type="number" name="port_activation" value="{{ old('port_activation', $company->port_activation) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">عدد أيام الإيقاف</label>
                        <input type="number" name="suspention_penalty_days" value="{{ old('suspention_penalty_days', $company->suspention_penalty_days) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">عدد الأيام المسموحة للإيقاف</label>
                        <input type="number" name="days_allowed_for_suspention" value="{{ old('days_allowed_for_suspention', $company->days_allowed_for_suspention) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">البريد لحل المشاكل</label>
                        <input type="email" name="email_problem" value="{{ old('email_problem', $company->email_problem) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">اسم المستخدم لحل المشاكل</label>
                        <input type="text" name="username_problem" value="{{ old('username_problem', $company->username_problem) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">كلمة المرور لحل المشاكل</label>
                        <input type="text" name="password_problem" value="{{ old('password_problem', $company->password_problem) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">البورت لحل المشاكل</label>
                        <input type="number" name="port_problem" value="{{ old('port_problem', $company->port_problem) }}" class="w-full border rounded p-2">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1 font-medium">بيانات التهيئة (Configuration)</label>
                        <input type="text" name="configuration" value="{{ old('configuration', $company->configuration) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">نسخة إلى (CC)</label>
                        <input type="email" name="cc" value="{{ old('cc', $company->cc) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">نسخة مخفية (BCC)</label>
                        <input type="email" name="bcc" value="{{ old('bcc', $company->bcc) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">نسخة مخفية إضافية (BCC2)</label>
                        <input type="email" name="bcc2" value="{{ old('bcc2', $company->bcc2) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">اسم المستخدم للبوابة</label>
                        <input type="text" name="user_portal" value="{{ old('user_portal', $company->user_portal) }}" class="w-full border rounded p-2">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">كلمة مرور البوابة</label>
                        <input type="text" name="password_portal" value="{{ old('password_portal', $company->password_portal) }}" class="w-full border rounded p-2">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        حفظ البيانات
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
