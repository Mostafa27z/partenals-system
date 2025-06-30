<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-bold">كل الطلبات</h2></x-slot>
<form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
    <input type="text" name="phone" value="{{ request('phone') }}" placeholder="رقم الهاتف" class="p-2 border rounded">
    <input type="text" name="nid" value="{{ request('nid') }}" placeholder="الرقم القومي" class="p-2 border rounded">
    <select name="type" class="p-2 border rounded">
        <option value="">-- نوع الطلب --</option>
        <option value="stop" {{ request('type') == 'stop' ? 'selected' : '' }}>إيقاف</option>
        <option value="resell" {{ request('type') == 'resell' ? 'selected' : '' }}>إعادة بيع</option>
        <option value="change_plan" {{ request('type') == 'change_plan' ? 'selected' : '' }}>تغيير نظام</option>
        <option value="resume" {{ request('type') == 'resume' ? 'selected' : '' }}>إعادة تشغيل</option>
        <option value="pause" {{ request('type') == 'pause' ? 'selected' : '' }}>إيقاف مؤقت</option>
        <option value="change_chip" {{ request('type') == 'change_chip' ? 'selected' : '' }}>تغيير شريحة</option>
    </select>
    <input type="date" name="from" value="{{ request('from') }}" class="p-2 border rounded">
    <input type="date" name="to" value="{{ request('to') }}" class="p-2 border rounded">
    <input type="text" name="provider" value="{{ request('provider') }}" placeholder="المشغل (Vodafone...)" class="p-2 border rounded">

    <div class="md:col-span-6 text-end">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">🔍 بحث</button>
    </div>
</form>

    <form method="POST" action="{{ route('requests.bulk-action') }}">
    @csrf
    <div class="flex items-center justify-between mb-4">
        <div>
            <select name="new_status" class="border p-2 rounded" required>
                <option value="">-- اختر الحالة الجديدة --</option>
                <option value="pending">معلق</option>
                <option value="inprogress">تحت التنفيذ</option>
                <option value="done">تم</option>
                <option value="cancelled">ملغي</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" name="action" value="change_status" class="bg-yellow-500 text-white px-4 py-2 rounded">
                ✅ تغيير الحالة
            </button>
            <button type="submit" name="action" value="export" class="bg-green-600 text-white px-4 py-2 rounded">
                📁 تصدير المحدد
            </button>
            <button type="submit" name="action" value="change_and_export" class="bg-blue-700 text-white px-4 py-2 rounded">
                🛠 تغيير + تصدير
            </button>
        </div>
    </div>

    <table class="min-w-full text-center border">
        <thead>
            <tr class="bg-gray-100">
                <th><input type="checkbox" onclick="toggleAll(this)"></th>
                <th>الرقم</th>
                <th>النوع</th>
                <th>المشغل</th>
                <th>الحالة</th>
                <th>تاريخ الطلب</th>
                <th>تفاصيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
                <tr>
                    <td><input type="checkbox" name="selected_requests[]" value="{{ $req->id }}"></td>
                    <td>{{ $req->line->phone_number ?? '-' }}</td>
                    <td>{{ $req->request_type }}</td>
                    <td>{{ $req->line->provider ?? '-' }}</td>
                    <td>{{ $req->status }}</td>
                    <td>{{ $req->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('requests.show', $req->id) }}" class="text-blue-600 underline">عرض</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>


    {{ $requests->links() }}

    @push('scripts')
<script>
    function toggleAll(source) {
        document.querySelectorAll('input[name="selected_requests[]"]').forEach(cb => cb.checked = source.checked);
    }
</script>
@endpush

</x-app-layout>
