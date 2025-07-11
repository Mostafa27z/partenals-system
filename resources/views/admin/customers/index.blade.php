<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-bold text-gray-800">{{ __('messages.Customers List') }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('customers.trashed') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow text-sm">
                    🗑️ {{ __('messages.Deleted Customers') }}
                </a>
                <a href="{{ route('customers.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm">
                    + {{ __('messages.New Customer') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filter Form -->
            <div class="bg-white p-4 rounded shadow-sm">
                <form method="GET" action="{{ route('customers.index') }}" class="flex flex-wrap gap-4 items-center">
                    <input type="text" name="phone_number" value="{{ request('phone_number') }}" placeholder="{{ __('messages.Phone Number') }}" class="input input-bordered w-full sm:w-40" />
                    <input type="text" name="national_id" value="{{ request('national_id') }}" placeholder="{{ __('messages.National ID') }}" class="input input-bordered w-full sm:w-40" />

                    <button class="btn btn-primary">{{ __('messages.Search') }}</button>
                    <a href="{{ route('customers.export') }}" class="btn btn-success">{{ __('messages.Export to Excel') }}</a>
                </form>
            </div>

            <!-- Customers Table -->
            <div class="bg-white overflow-x-auto rounded shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-center" dir="rtl">
                    <thead class="bg-gray-50 text-gray-700 text-sm">
                        <tr>
                            <th class="px-4 py-2">{{ __('messages.Full Name') }}</th>
                            <th class="px-4 py-2">{{ __('messages.National ID') }}</th>
                            <th class="px-4 py-2">{{ __('messages.Invoices') }}</th>
                            <th class="px-4 py-2" colspan="3">{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @foreach ($customers as $customer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $customer->full_name }}</td>
                                <td class="px-4 py-2">{{ $customer->national_id }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('customers.invoices', $customer) }}" class="text-green-600 hover:underline">{{ __('messages.View Invoices') }}</a>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:underline">{{ __('messages.View') }}</a>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('customers.edit', $customer) }}" class="text-yellow-500 hover:underline">{{ __('messages.Edit') }}</a>
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('{{ __('messages.Are you sure?') }}')" class="text-red-600 hover:underline">
                                            {{ __('messages.Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4 px-4">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
