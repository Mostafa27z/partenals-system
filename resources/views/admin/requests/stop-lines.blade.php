<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">طلبات إيقاف الخطوط</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- الرسائل --}}
        @if ($errors->has('status'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded shadow">
                {{ $errors->first('status') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" dir="rtl">
    <input type="text" name="nid" value="{{ request('nid') }}" placeholder="الرقم القومي" class="border p-2 rounded">
    <input type="text" name="phone" value="{{ request('phone') }}" placeholder="رقم الهاتف" class="border p-2 rounded">

    <select name="requested_by" class="border p-2 rounded">
        <option value="">-- أنشئ بواسطة --</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ request('requested_by') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>

    <select name="done_by" class="border p-2 rounded">
        <option value="">-- تم بواسطة --</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ request('done_by') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>

    <input type="date" name="from" value="{{ request('from') }}" class="border p-2 rounded" placeholder="من تاريخ">
    <input type="date" name="to" value="{{ request('to') }}" class="border p-2 rounded" placeholder="إلى تاريخ">
{{-- مزود الخدمة --}}
<select name="provider" class="border p-2 rounded">
    <option value="">-- مزود الخدمة --</option>
    @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $provider)
        <option value="{{ $provider }}" {{ request('provider') == $provider ? 'selected' : '' }}>
            {{ $provider }}
        </option>
    @endforeach
</select>

{{-- الموزع --}}
<input type="text" name="distributor" value="{{ request('distributor') }}" placeholder="الموزع" class="border p-2 rounded">

    <div class="md:col-span-4 flex justify-end">
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🔍 بحث</button>
    </div>
</form>

        <div class="bg-white p-6 rounded shadow">
            @if($requests->count())
                <table class="min-w-full text-center border divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">العميل</th>
                            <th class="px-4 py-2">الرقم القومي</th>
                            <th class="px-4 py-2">رقم الخط</th>
                            <th class="px-4 py-2">نوع الطلب</th>
                            <th class="px-4 py-2">الموزع</th>
                            <th class="px-4 py-2">المزود</th>
                            <th class="px-4 py-2">تاريخ آخر فاتورة</th>
                            <th class="px-4 py-2">أنشئ بواسطة</th>
                            <th class="px-4 py-2">تم بواسطة</th>
                            <th class="px-4 py-2">تغيير الحالة </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td>{{ $request->line->customer->full_name ?? '-' }}</td>
                                <td>{{ $request->line->customer->national_id ?? '-' }}</td>
                                <td>{{ $request->line->phone_number }}</td>
                                <td>{{ $request->request_type }}</td>
                                <td>{{ $request->line->distributor ?? '-' }}</td>
                                <td>{{ $request->line->provider ?? '-' }}</td>

                                <td>{{ $request->stopDetails->last_invoice_date ?? '-' }}</td>
                                <td>{{ $request->requestedBy?->name ?? 'System' }}</td>
                                <td>{{ $request->doneBy?->name ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('requests.update-status', $request->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من تغيير الحالة؟')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="old_status" value="{{ $request->status }}">
                                        <select name="status" class="px-2 py-1 rounded border @if($request->status == 'pending') bg-yellow-100 text-yellow-800 
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

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">{{ $requests->links() }}</div>
            @else
                <p class="text-gray-600">لا توجد طلبات حالياً.</p>
            @endif
        </div>
    </div>
</x-app-layout>
