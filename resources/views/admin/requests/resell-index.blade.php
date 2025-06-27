<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">طلبات إعادة البيع</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <input type="text" name="name" value="{{ request('name') }}" class="border p-2 rounded" placeholder="اسم العميل">
    <input type="text" name="nid" value="{{ request('nid') }}" class="border p-2 rounded" placeholder="الرقم القومي">
    <input type="text" name="provider" value="{{ request('provider') }}" class="border p-2 rounded" placeholder="مزود الخدمة">

    <select name="resell_type" class="border p-2 rounded">
        <option value="">-- نوع الطلب --</option>
        <option value="chip" {{ request('resell_type') == 'chip' ? 'selected' : '' }}>على الشريحة</option>
        <option value="branch" {{ request('resell_type') == 'branch' ? 'selected' : '' }}>في الفرع</option>
    </select>

    <select name="status" class="border p-2 rounded">
        <option value="">-- الحالة --</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
        <option value="inprogress" {{ request('status') == 'inprogress' ? 'selected' : '' }}>تحت التنفيذ</option>
        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>تم</option>
    </select>

    <input type="date" name="from" value="{{ request('from') }}" class="border p-2 rounded">
    <input type="date" name="to" value="{{ request('to') }}" class="border p-2 rounded">

    <div class="col-span-4 flex justify-end">
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🔍 بحث</button>
    </div>
</form>


        <div class="bg-white p-6 rounded shadow">
            @if($requests->count())
                <table class="min-w-full text-center border divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th>رقم الخط</th>
                            <th>العميل</th>
                            <th>نوع الطلب</th>
                            <th>تاريخ الطلب</th>
                            <th>الحالة</th>
                            <th>التفاصيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td>{{ $request->line->phone_number }}</td>
                                <td>{{ $request->line->customer->full_name ?? '-' }}</td>
                                <td>{{ $request->resellDetails->resell_type == 'chip' ? 'على الشريحة' : 'في الفرع' }}</td>
                                <td>{{ $request->resellDetails->request_date }}</td>
                                <td>
                                    <form action="{{ route('requests.update-status', $request->id) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من تغيير حالة الطلب؟')">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="old_status" value="{{ $request->status }}">

                                        <select name="status"
                                                class="rounded px-2 py-1 border
                                                    @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($request->status == 'inprogress') bg-blue-100 text-blue-800
                                                    @elseif($request->status == 'done') bg-green-100 text-green-800
                                                    @endif"
                                                onchange="this.form.submit()">

                                            <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>معلق</option>
                                            <option value="inprogress" {{ $request->status == 'inprogress' ? 'selected' : '' }}>تحت التنفيذ</option>
                                            <option value="done" {{ $request->status == 'done' ? 'selected' : '' }}>تم</option>
                                        </select>
                                    </form>
                                </td>

                                <td>
                                    <a href="{{ route('requests.resell.details', $request->id) }}"
                                    class="text-blue-600 hover:underline">📄 عرض التفاصيل</a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $requests->appends(request()->query())->links() }}
                </div>
            @else
                <p class="text-gray-600">لا توجد طلبات حالياً.</p>
            @endif
        </div>
    </div>
</x-app-layout>
