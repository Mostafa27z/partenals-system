<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            معلومات الشركة
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('company.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700">اسم الشركة</label>
                        <input type="text" name="company_name" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">وصف الشركة</label>
                        <textarea name="company_description" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700">شعار الشركة</label>
                        <input type="file" name="company_logo" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <label class="block text-gray-700">تفعيل البريد الإلكتروني</label>
                        <input type="text" name="email_activation" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">اسم المستخدم للتفعيل</label>
                        <input type="text" name="active_username" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">كلمة المرور للتفعيل</label>
                        <input type="text" name="active_password" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">المنفذ للتفعيل</label>
                        <input type="number" name="active_port" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">عدد أيام العقوبة</label>
                        <input type="number" name="suspension_penalty_days" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">عدد الأيام المسموح بها للتعليق</label>
                        <input type="number" name="allowed_suspension_days" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">البريد الإلكتروني للمشاكل</label>
                        <input type="text" name="email_problem" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">اسم المستخدم للمشاكل</label>
                        <input type="text" name="problem_username" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">كلمة المرور للمشاكل</label>
                        <input type="text" name="problem_password" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">المنفذ للمشاكل</label>
                        <input type="number" name="problem_port" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700">إعدادات SMTP أو السيرفر</label>
                        <input type="text" name="smtp_configuration" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">CC</label>
                        <input type="text" name="cc" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">BCC</label>
                        <input type="text" name="bcc" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">BCC2</label>
                        <input type="text" name="bcc2" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">اسم المستخدم للبوابة</label>
                        <input type="text" name="portal_username" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700">كلمة المرور للبوابة</label>
                        <input type="text" name="portal_password" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                    </div>
                </div>

                <div class="mt-6 text-left">
                    <button type="submit" class="bg-blue-600 text-black px-6 py-2 rounded hover:bg-blue-700 shadow">
                        حفظ البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
