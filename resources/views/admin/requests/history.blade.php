<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 leading-tight">📁 تاريخ الطلبات (تمت)</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('requests.history') }}" class="bg-white p-4 rounded shadow mb-4 flex flex-wrap gap-4 items-center">
            <input type="text" name="phone" value="{{ request('phone') }}" placeholder="رقم الهاتف" class="input input-bordered w-full sm:w-40" />
            <input type="text" name="nid" value="{{ request('nid') }}" placeholder="الرقم القومي" class="input input-bordered w-full sm:w-40" />
            <input type="text" name="provider" value="{{ request('provider') }}" placeholder="المزود" class="input input-bordered w-full sm:w-40" />
            <input type="date" name="from" value="{{ request('from') }}" class="input input-bordered w-full sm:w-40" />
            <input type="date" name="to" value="{{ request('to') }}" class="input input-bordered w-full sm:w-40" />
            <select name="type" class="input input-bordered w-full sm:w-40">
                <option value="">-- النوع --</option>
                @foreach(['resell', 'change_plan', 'change_chip', 'pause', 'resume', 'change_date', 'change_distributor', 'stop'] as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ __('طلب ' . str_replace('_', ' ', $type)) }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-primary">🔍 بحث</button>
        </form>

        <div class="bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">رقم الهاتف</th>
                        <th class="px-4 py-2">الاسم</th>
                        <th class="px-4 py-2">الرقم القومي</th>
                        <th class="px-4 py-2">نوع الطلب</th>
                        <th class="px-4 py-2">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $request->line->phone_number }}</td>
                            <td class="px-4 py-2">{{ $request->line->customer?->full_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $request->line->customer?->national_id ?? '-' }}</td>
                            <td class="px-4 py-2">{{ __('طلب ' . str_replace('_', ' ', $request->request_type)) }}</td>
                            <td class="px-4 py-2">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-gray-500">لا توجد طلبات منتهية حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4 px-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
