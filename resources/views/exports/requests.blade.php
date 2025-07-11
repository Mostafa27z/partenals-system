<div class="overflow-x-auto">
    <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded shadow-sm text-sm">
        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
            <tr>
                <th class="px-4 py-2 border dark:border-gray-600 text-right">{{ __('Phone') }}</th>
                <th class="px-4 py-2 border dark:border-gray-600 text-right">{{ __('Request Type') }}</th>
                <th class="px-4 py-2 border dark:border-gray-600 text-right">{{ __('Provider') }}</th>
                <th class="px-4 py-2 border dark:border-gray-600 text-right">{{ __('Status') }}</th>
                <th class="px-4 py-2 border dark:border-gray-600 text-right">{{ __('Date') }}</th>
            </tr>
        </thead>
        <tbody class="text-gray-800 dark:text-gray-100">
            @foreach($requests as $r)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-4 py-2 border dark:border-gray-600 text-right">{{ $r->line->phone_number ?? '-' }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600 text-right">{{ __($r->request_type) }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600 text-right">{{ $r->line->provider ?? '-' }}</td>
                    <td class="px-4 py-2 border dark:border-gray-600 text-right">
                        <span class="inline-block px-2 py-1 rounded-full 
                            @class([
                                'bg-green-100 text-green-800' => $r->status === 'done',
                                'bg-yellow-100 text-yellow-800' => $r->status === 'pending',
                                'bg-red-100 text-red-800' => $r->status === 'cancelled',
                                'bg-blue-100 text-blue-800' => $r->status === 'in_progress',
                            ])">
                            {{ __($r->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border dark:border-gray-600 text-right">{{ $r->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
