<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight" dir="rtl">
            {{ __('messages.deleted_lines') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-4 lg:px-8" dir="rtl">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow" role="alert" dir="rtl">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
            <table class="min-w-full text-center divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">{{ __('messages.phone_number') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">{{ __('messages.customer') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">{{ __('messages.national_id') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">{{ __('messages.provider') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">{{ __('messages.deleted_at') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lines as $line)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">{{ $line->phone_number }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $line->customer->full_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $line->customer->national_id ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $line->provider }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $line->deleted_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap flex justify-center gap-3 flex-wrap">
                                <form action="{{ route('lines.restore', $line->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="text-green-600 hover:underline focus:outline-none focus:ring-2 focus:ring-green-400 rounded"
                                        onclick="return confirm('{{ __('messages.restore_confirm') }}')">
                                        ♻️ {{ __('messages.restore') }}
                                    </button>
                                </form>

                                <form action="{{ route('lines.forceDelete', $line->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:underline focus:outline-none focus:ring-2 focus:ring-red-400 rounded"
                                        onclick="return confirm('{{ __('messages.force_delete_confirm') }}')">
                                        🗑️ {{ __('messages.force_delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-gray-500 py-6 text-center">
                                {{ __('messages.no_deleted_lines') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $lines->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
