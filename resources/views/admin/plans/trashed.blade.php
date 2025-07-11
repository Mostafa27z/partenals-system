<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                🗑️ {{ __('messages.Deleted Plans') }}
            </h2>
            <a href="{{ route('plans.index') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                ⬅️ {{ __('messages.Back to Plans') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        @if($plans->count() > 0)
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-center">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">{{ __('messages.Name') }}</th>
                            <th class="px-4 py-2">{{ __('messages.Price') }}</th>
                            <th class="px-4 py-2">{{ __('messages.Provider') }}</th>
                            <th class="px-4 py-2">{{ __('messages.Plan Code') }}</th>
                            <th class="px-4 py-2">{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($plans as $plan)
                            <tr>
                                <td class="px-4 py-2">{{ $plan->name }}</td>
                                <td class="px-4 py-2">{{ $plan->price }}</td>
                                <td class="px-4 py-2">{{ $plan->provider ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $plan->plan_code ?? '-' }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <form action="{{ route('plans.restore', $plan->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:underline">
                                            🔄 {{ __('messages.Restore') }}
                                        </button>
                                    </form>

                                    <form action="{{ route('plans.force-delete', $plan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('messages.Confirm Permanent Delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">
                                            🗑️ {{ __('messages.Delete Permanently') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $plans->links() }}
            </div>
        @else
            <div class="bg-white p-6 rounded shadow text-center text-gray-500">
                {{ __('messages.No Deleted Plans') }}
            </div>
        @endif
    </div>
</x-app-layout>
