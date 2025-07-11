<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 leading-tight" dir="rtl">
            {{ __('messages.requests_history') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" dir="rtl">
        <form method="GET" action="{{ route('requests.history') }}" 
              class="bg-white p-4 rounded shadow mb-4 flex flex-wrap gap-4 items-center justify-start">
            
            <input type="text" name="phone" value="{{ request('phone') }}" 
                   placeholder="{{ __('messages.phone_number') }}" 
                   class="input input-bordered w-full sm:w-40" />
                   
            <input type="text" name="nid" value="{{ request('nid') }}" 
                   placeholder="{{ __('messages.national_id') }}" 
                   class="input input-bordered w-full sm:w-40" />
                   
            <input type="text" name="provider" value="{{ request('provider') }}" 
                   placeholder="{{ __('messages.provider') }}" 
                   class="input input-bordered w-full sm:w-40" />
                   
            <input type="date" name="from" value="{{ request('from') }}" 
                   class="input input-bordered w-full sm:w-40" />
                   
            <input type="date" name="to" value="{{ request('to') }}" 
                   class="input input-bordered w-full sm:w-40" />
                   
            <select name="type" class="input input-bordered w-full sm:w-40">
                <option value="">{{ __('messages.select_type') }}</option>
                @foreach(['resell', 'change_plan', 'change_chip', 'pause', 'resume', 'change_date', 'change_distributor', 'stop'] as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ __('messages.request_type_' . $type) }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">
                🔍 {{ __('messages.search') }}
            </button>
        </form>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">{{ __('messages.phone_number') }}</th>
                        <th class="px-4 py-2">{{ __('messages.name') }}</th>
                        <th class="px-4 py-2">{{ __('messages.national_id') }}</th>
                        <th class="px-4 py-2">{{ __('messages.request_type') }}</th>
                        <th class="px-4 py-2">{{ __('messages.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        <tr class="border-t hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-2">{{ $request->line->phone_number }}</td>
                            <td class="px-4 py-2">{{ $request->line->customer?->full_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $request->line->customer?->national_id ?? '-' }}</td>
                            <td class="px-4 py-2">{{ __('messages.request_type_' . $request->request_type) }}</td>
                            <td class="px-4 py-2">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-gray-500">{{ __('messages.no_completed_requests') }}</td>
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
