<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold" dir="rtl">{{ __('messages.all_requests') }}</h2>
    </x-slot>

    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6" dir="rtl">
        <input type="text" name="phone" value="{{ request('phone') }}" placeholder="{{ __('messages.phone_number') }}" class="p-2 border rounded w-full" />
        <input type="text" name="nid" value="{{ request('nid') }}" placeholder="{{ __('messages.national_id') }}" class="p-2 border rounded w-full" />
        <select name="type" class="p-2 border rounded w-full">
            <option value="">{{ __('messages.select_request_type') }}</option>
            <option value="stop" {{ request('type') == 'stop' ? 'selected' : '' }}>{{ __('messages.type_stop') }}</option>
            <option value="resell" {{ request('type') == 'resell' ? 'selected' : '' }}>{{ __('messages.type_resell') }}</option>
            <option value="change_plan" {{ request('type') == 'change_plan' ? 'selected' : '' }}>{{ __('messages.type_change_plan') }}</option>
            <option value="resume" {{ request('type') == 'resume' ? 'selected' : '' }}>{{ __('messages.type_resume') }}</option>
            <option value="pause" {{ request('type') == 'pause' ? 'selected' : '' }}>{{ __('messages.type_pause') }}</option>
            <option value="change_chip" {{ request('type') == 'change_chip' ? 'selected' : '' }}>{{ __('messages.type_change_chip') }}</option>
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="p-2 border rounded w-full" />
        <input type="date" name="to" value="{{ request('to') }}" class="p-2 border rounded w-full" />
        <input type="text" name="provider" value="{{ request('provider') }}" placeholder="{{ __('messages.provider_placeholder') }}" class="p-2 border rounded w-full" />

        <div class="col-span-full text-end">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                🔍 {{ __('messages.search') }}
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('requests.bulk-action') }}" dir="rtl">
        @csrf
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <select name="new_status" class="border p-2 rounded max-w-xs" required>
                <option value="">{{ __('messages.select_new_status') }}</option>
                <option value="pending">{{ __('messages.status_pending') }}</option>
                <option value="inprogress">{{ __('messages.status_inprogress') }}</option>
                <option value="done">{{ __('messages.status_done') }}</option>
                <option value="cancelled">{{ __('messages.status_cancelled') }}</option>
            </select>

            <div class="flex flex-wrap gap-2">
                <button type="submit" name="action" value="change_status" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                    ✅ {{ __('messages.change_status') }}
                </button>
                <button type="submit" name="action" value="export" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    📁 {{ __('messages.export_selected') }}
                </button>
                <button type="submit" name="action" value="change_and_export" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition">
                    🛠 {{ __('messages.change_and_export') }}
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-center border border-gray-300 rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2"><input type="checkbox" onclick="toggleAll(this)"></th>
                        <th class="p-2">{{ __('messages.number') }}</th>
                        <th class="p-2">{{ __('messages.type') }}</th>
                        <th class="p-2">{{ __('messages.provider') }}</th>
                        <th class="p-2">{{ __('messages.status') }}</th>
                        <th class="p-2">{{ __('messages.request_date') }}</th>
                        <th class="p-2">{{ __('messages.details') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    <tr class="border-t hover:bg-gray-50 transition-colors">
                        <td class="p-2"><input type="checkbox" name="selected_requests[]" value="{{ $req->id }}"></td>
                        <td class="p-2">{{ $req->line->phone_number ?? '-' }}</td>
                        <td class="p-2">{{ __('messages.request_type_'.$req->request_type) ?? $req->request_type }}</td>
                        <td class="p-2">{{ $req->line->provider ?? '-' }}</td>
                        <td class="p-2">{{ __('messages.status_'.$req->status) ?? $req->status }}</td>
                        <td class="p-2">{{ $req->created_at->format('Y-m-d') }}</td>
                        <td class="p-2">
                            <a href="{{ route('requests.show', $req->id) }}" class="text-blue-600 underline hover:text-blue-800 transition">
                                {{ __('messages.view') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-6">
        {{ $requests->links() }}
    </div>

    @push('scripts')
    <script>
        function toggleAll(source) {
            document.querySelectorAll('input[name="selected_requests[]"]').forEach(cb => cb.checked = source.checked);
        }
    </script>
    @endpush
</x-app-layout>
