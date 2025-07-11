<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('messages.Edit Customer') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow-sm">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded shadow">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.Full Name') }}</label>
                        <input type="text" name="full_name" class="form-input mt-1 block w-full rounded-md border-gray-300" value="{{ old('full_name', $customer->full_name) }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.National ID') }}</label>
                        <input type="text" name="national_id" class="form-input mt-1 block w-full rounded-md border-gray-300" value="{{ old('national_id', $customer->national_id) }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.Birth Date') }}</label>
                        <input type="date" name="birth_date" class="form-input mt-1 block w-full rounded-md border-gray-300" value="{{ old('birth_date', $customer->birth_date) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.Email') }}</label>
                        <input type="email" name="email" class="form-input mt-1 block w-full rounded-md border-gray-300" value="{{ old('email', $customer->email) }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">{{ __('messages.Address') }}</label>
                        <input type="text" name="address" class="form-input mt-1 block w-full rounded-md border-gray-300" value="{{ old('address', $customer->address) }}">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        {{ __('messages.Update') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- خطوط العميل -->
        <div class="bg-white mt-8 p-6 rounded shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('messages.Customer Lines') }}</h3>

            @if($customer->lines->count())
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border border-gray-200 text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr class="text-center">
                                <th class="px-4 py-2 border">{{ __('messages.Phone Number') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.Line Type') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.Provider') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.Plan') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($customer->lines as $line)
                                <tr class="text-center hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $line->phone_number }}</td>
                                    <td class="px-4 py-2 border">{{ $line->line_type == 'prepaid' ? __('messages.Prepaid') : __('messages.Postpaid') }}</td>
                                    <td class="px-4 py-2 border">{{ $line->provider }}</td>
                                    <td class="px-4 py-2 border">{{ $line->plan->name ?? '-' }}</td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('customers.lines.edit', [$customer, $line]) }}" class="text-blue-600 hover:underline">{{ __('messages.Edit') }}</a>
                                        |
                                        <form action="{{ route('customers.lines.destroy', [$customer, $line]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('{{ __('messages.Are you sure to delete this line?') }}')" class="text-red-600 hover:underline">{{ __('messages.Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('messages.No lines found for this customer.') }}</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('customers.lines.create', $customer) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    + {{ __('messages.Add New Line') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
