<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">جميع الخطوط</h2>
            <a href="{{ route('lines.for-sale') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">
                📦 خطوط للبيع
            </a>
            <a href="{{ route('lines.trashed') }}" class="btn btn-secondary">🗑️ سلة المحذوفات</a>

        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" dir="rtl">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        {{-- نموذج البحث --}}
        <div class="mb-4 flex flex-wrap gap-4 items-center">
            <a href="{{ route('lines.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">➕ خط جديد</a>
            <a href="{{ route('lines.import.form') }}" class="btn btn-secondary">📥 رفع ملف Excel</a>
            <a href="{{ route('lines.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">⬇️ تصدير الكل</a>

            <form method="GET" action="{{ route('lines.all') }}" class="flex flex-wrap gap-4 mt-2 sm:mt-0">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="رقم الهاتف" class="input input-bordered w-full sm:w-40" />
                <input type="text" name="nid" value="{{ request('nid') }}" placeholder="الرقم القومي" class="input input-bordered w-full sm:w-40" />
                <input type="text" name="provider" value="{{ request('provider') }}" placeholder="المزود" class="input input-bordered w-full sm:w-40" />
                <input type="text" name="distributor" value="{{ request('distributor') }}" placeholder="الموزع" class="input input-bordered w-full sm:w-40" />
                <select name="plan_id" class="input input-bordered w-full sm:w-40">
                    <option value="">-- النظام --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-primary">🔍 بحث</button>
            </form>
        </div>

        {{-- نموذج التصدير --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form method="POST" action="{{ route('lines.export.selected') }}">
                @csrf

        <table class="min-w-full divide-y divide-gray-200 text-center" dir="rtl">
    <thead class="bg-gray-50">
        <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th class="px-4 py-2">رقم الهاتف</th>
            <th class="px-4 py-2">الرقم القومي</th>
            <th class="px-4 py-2">اسم العميل</th>
            <th class="px-4 py-2">الحالة</th>
            <th class="px-4 py-2">العمليات</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach($lines as $line)
            <tr>
                <td>
                    <input type="checkbox" name="selected_lines[]" value="{{ $line->id }}" class="line-checkbox">
                </td>
                <td class="px-4 py-2">{{ $line->phone_number }}</td>
                <td class="px-4 py-2">{{ $line->customer->national_id ?? '-' }}</td>
                <td class="px-4 py-2">{{ $line->customer->full_name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $line->status === 'active' ? 'نشط' : 'غير نشط' }}</td>
                <td class="px-4 py-2 space-x-2 flex justify-center gap-2 flex-wrap">
                    <button type="button" class="text-blue-600 hover:underline" onclick="toggleDetails({{ $line->id }})">
                        👁️ عرض بيانات الخط
                    </button>
                    <a href="{{ route('lines.edit', $line->id) }}" class="text-yellow-600 hover:underline">✏️ تعديل</a>
                    <button type="button" class="text-red-600 hover:underline" onclick="confirmDelete({{ $line->id }})">🗑 حذف</button>
                    @if($line->plan)
                        <a href="{{ route('invoices.create', $line) }}" class="text-green-600 hover:underline">💳 دفع</a>
                    @endif
                </td>
            </tr>

            <!-- التفاصيل -->
            <tr id="line-details-{{ $line->id }}" style="display: none;" class="bg-gray-100 text-lg">
                <td colspan="6" class="p-4 text-start">
                    <div><strong>المزود:</strong> {{ $line->provider }}</div>
                    <div><strong>نوع الخط:</strong> {{ $line->line_type === 'prepaid' ? 'مدفوع مسبقاً' : 'فاتورة' }}</div>
                    <div><strong>النظام:</strong> {{ $line->plan->name ?? '-' }}</div>
                    <div><strong>الموزع:</strong> {{ $line->distributor ?? '-' }}</div>
                    <div><strong>GCode:</strong> {{ $line->gcode }}</div>
                    <div><strong>تاريخ الربط:</strong>{{ \Carbon\Carbon::parse($line->attached_at)->format('Y-m-d') }} </div>
                    <div><strong>آخر فاتورة:</strong> {{ \Carbon\Carbon::parse($line->last_invoice_date)->format('Y-m-d') }}</div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
<script>
    function toggleDetails(id) {
        const row = document.getElementById('line-details-' + id);
        if (row.style.display === 'none') {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
</script>
@endpush


                <div class="mt-4 px-4 text-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                        ⬇️ تصدير المحدد
                    </button>
                </div>
            </form>

            <div class="mt-4 px-4">
                {{ $lines->links() }}
            </div>
        </div>
    </div>
    <form action="{{ route('requests.stop.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex items-center gap-4">
    @csrf
    <input type="file" name="file" accept=".xlsx" required class="block border rounded p-2">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ⬆️ رفع شيت طلبات الإيقاف
    </button>
</form>
<form action="{{ route('requests.resell.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex items-center gap-4">
    @csrf
    <input type="file" name="file" accept=".xlsx" required class="block border rounded p-2">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ⬆️ رفع شيت طلبات إعادة البيع
    </button>
</form>
<form action="{{ route('requests.change-plan.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex items-center gap-4">
    @csrf
    <input type="file" name="file" accept=".xlsx" required class="border p-2 rounded">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ⬆️ رفع شيت طلبات تغيير النظام
    </button>
</form>
<form action="{{ route('requests.change-chip.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex items-center gap-4">
    @csrf
    <input type="file" name="file" accept=".xlsx" required class="border p-2 rounded">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ⬆️ رفع شيت طلبات تغيير الشريحة
    </button>
</form>
<form action="{{ route('requests.change-distributor.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex items-center gap-4">
    @csrf
    <input type="file" name="file" accept=".xlsx" required class="border p-2 rounded">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ⬆️ رفع شيت طلبات تغيير الموزع
    </button>
</form>
<form action="{{ route('requests.change-date.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex items-center gap-4">
    @csrf
    <input type="file" name="file" accept=".xlsx" required class="border p-2 rounded">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ⬆️ رفع شيت طلبات تغيير تاريخ الفاتورة
    </button>
</form>
<form action="{{ route('requests.resume.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex items-center gap-4">
    @csrf
    <input type="file" name="file" accept=".xlsx" required class="border p-2 rounded">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ⬆️ رفع شيت طلبات إعادة تشغيل الخط
    </button>
</form>
<form action="{{ route('requests.pause.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex items-center gap-4">
    @csrf
    <input type="file" name="file" accept=".xlsx" required class="border p-2 rounded">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
        ⬆️ رفع شيت طلبات إيقاف مؤقت
    </button>
</form>

@if($lines->count() === 1)
    @php
        $line = $lines->first();
    @endphp

    <div class="mt-6 p-4 bg-blue-50 rounded shadow">
        <h3 class="font-bold mb-2">📱 رقم: {{ $line->phone_number }}</h3>

        <form method="GET" onsubmit="return redirectToCreateRequest(event)">
            <label for="request-type" class="block mb-1 font-medium">اختر نوع الطلب:</label>
            <select id="request-type" class="input input-bordered w-full max-w-xs" required>
                <option value="">-- اختر النوع --</option>
                <option value="resell">إعادة بيع</option>
                <option value="change-plan">تغيير النظام</option>
                <option value="change-chip">تغيير شريحة</option>
                <option value="pause">إيقاف مؤقت</option>
                <option value="resume">إعادة تشغيل</option>
                <option value="change-date">تغيير تاريخ</option>
                <option value="change-distributor">تغيير موزع</option>
                <option value="stop-line"> ايقاف خط</option>
            </select>

            <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                ➕ إنشاء الطلب
            </button>
        </form>
    </div>

    @push('scripts')
    <script>
        function redirectToCreateRequest(event) {
            event.preventDefault();
            const type = document.getElementById('request-type').value;
            if (!type) {
                alert("❌ اختر نوع الطلب أولاً");
                return false;
            }

            const lineId = {{ $line->id }};
            const baseUrl = {
                'resell': '/admin/requests/resell/' + lineId,
                'change-plan': '/admin/requests/change-plan/' + lineId,
                'change-chip': '/admin/requests/change-chip/' + lineId,
                'pause': '/admin/requests/pause/' + lineId,
                'resume': '/admin/requests/resume/' + lineId + '/create',
                'change-date': '/admin/requests/change-date/' + lineId,
                'change-distributor': '/admin/requests/change-distributor/' + lineId,
                'stop-line': '/admin/requests/stop/' + lineId,
            };

            if (baseUrl[type]) {
                window.location.href = baseUrl[type];
            } else {
                alert("❌ نوع الطلب غير مدعوم.");
            }
        }
    </script>
    @endpush
@endif

    {{-- نموذج الحذف المخفي --}}
    <form method="POST" id="delete-form" style="">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            document.getElementById('select-all').addEventListener('change', function () {
                document.querySelectorAll('.line-checkbox').forEach(cb => cb.checked = this.checked);
            });

            function confirmDelete(lineId) {
                if (confirm('هل أنت متأكد من حذف الخط؟')) {
                    const form = document.getElementById('delete-form');
                    form.action = `/admin/lines/${lineId}`;
                    form.submit();
                }
            }
        </script>
    @endpush
</x-app-layout>
