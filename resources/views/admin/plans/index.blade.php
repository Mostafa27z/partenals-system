<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('messages.Manage Plans') }}</h2>
            <a href="{{ route('plans.trashed') }}" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700">
                🗑️ {{ __('messages.Deleted Plans') }}
            </a>
        </div>
    </x-slot>

    <div class="py-4 px-6" dir="rtl">
        <form method="GET" class="mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex gap-2 flex-wrap">
                <input name="search" placeholder="{{ __('messages.Search') }}..." value="{{ request('search') }}"
                       class="border border-gray-300 px-3 py-1.5 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <button class="bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700">{{ __('messages.Search') }}</button>
                <a href="{{ route('plans.export') }}" class="bg-green-600 text-white px-4 py-1.5 rounded hover:bg-green-700">
                    {{ __('messages.Export to Excel') }}
                </a>
            </div>
            <a href="{{ route('plans.create') }}" class="bg-purple-600 text-white px-5 py-2 rounded hover:bg-purple-700">
                + {{ __('messages.Add Plan') }}
            </a>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full border text-right shadow-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border px-3 py-2">{{ __('messages.Name') }}</th>
                        <th class="border px-3 py-2">{{ __('messages.Price') }}</th>
                        <th class="border px-3 py-2">{{ __('messages.Provider') }}</th>
                        <th class="border px-3 py-2">{{ __('messages.Provider Price') }}</th>
                        <th class="border px-3 py-2">{{ __('messages.Type') }}</th>
                        <th class="border px-3 py-2">{{ __('messages.Plan Code') }}</th>
                        <th class="border px-3 py-2">{{ __('messages.Description') }}</th>
                        <th class="border px-3 py-2">{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @forelse($plans as $plan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border px-3 py-2">{{ $plan->name }}</td>
                            <td class="border px-3 py-2">{{ $plan->price }}</td>
                            <td class="border px-3 py-2">{{ $plan->provider }}</td>
                            <td class="border px-3 py-2">{{ $plan->provider_price }}</td>
                            <td class="border px-3 py-2">{{ $plan->type }}</td>
                            <td class="border px-3 py-2">{{ $plan->plan_code }}</td>
                            <td class="border px-3 py-2">{{ $plan->penalty }}</td>
                            <td class="border px-3 py-2 flex gap-2 justify-center flex-wrap">
                                <a href="{{ route('plans.show', $plan->id) }}" class="text-green-600 hover:underline">{{ __('messages.View') }}</a>
                                <a href="{{ route('plans.edit', $plan->id) }}" class="text-blue-600 hover:underline">{{ __('messages.Edit') }}</a>
                                <form method="POST" action="{{ route('plans.destroy', $plan->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('{{ __('messages.Are you sure?') }}')" class="text-red-600 hover:underline">
                                        {{ __('messages.Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">{{ __('messages.No records found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $plans->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
