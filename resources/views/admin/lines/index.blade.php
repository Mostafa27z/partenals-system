<!-- resources/views/admin/lines/index.blade.php --> 
<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> 
            {{ __('messages.customer_lines_for', ['name' => $customer->full_name]) }}
        </h2> 
    </x-slot> 
 
    <div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8"> 
        <div class="mb-6 text-right"> 
            <a href="{{ route('customers.lines.create', $customer) }}" 
               class="inline-block bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 transition">
                + {{ __('messages.add_new_line') }}
            </a> 
        </div> 
 
        <div class="bg-white p-6 rounded shadow overflow-x-auto"> 
            @if ($customer->lines->count()) 
                <table class="min-w-full divide-y divide-gray-200 text-center"> 
                    <thead class="bg-gray-100"> 
                        <tr> 
                            <th class="px-4 py-2">{{ __('messages.phone_number') }}</th> 
                            <th class="px-4 py-2">{{ __('messages.line_type') }}</th> 
                            <th class="px-4 py-2">{{ __('messages.provider') }}</th> 
                            <th class="px-4 py-2">{{ __('messages.plan') }}</th> 
                            <th class="px-4 py-2">{{ __('messages.status') }}</th> 
                            <th class="px-4 py-2">{{ __('messages.payment_date') }}</th> 
                            <th class="px-4 py-2">{{ __('messages.actions') }}</th> 
                        </tr> 
                    </thead> 
                    <tbody class="bg-white divide-y divide-gray-200"> 
                        @foreach($customer->lines as $line) 
                            <tr> 
                                <td class="px-4 py-2 whitespace-nowrap">{{ $line->phone_number }}</td> 
                                <td class="px-4 py-2 whitespace-nowrap"> 
                                    {{ $line->line_type == 'prepaid' ? __('messages.prepaid') : __('messages.postpaid') }} 
                                </td> 
                                <td class="px-4 py-2 whitespace-nowrap">{{ $line->provider }}</td> 
                                <td class="px-4 py-2 whitespace-nowrap">{{ $line->plan->name ?? '-' }}</td> 
                                <td class="px-4 py-2 whitespace-nowrap"> 
                                    {{ $line->status === 'active' ? __('messages.active') : __('messages.inactive') }} 
                                </td> 
                                <td class="px-4 py-2 whitespace-nowrap">{{ $line->payment_date }}</td> 
                                <td class="px-4 py-2 whitespace-nowrap"> 
                                    <a href="{{ route('customers.lines.edit', [$customer, $line]) }}" 
                                       class="text-blue-600 hover:underline mr-2"> 
                                        {{ __('messages.edit') }} 
                                    </a> 
                                    <form action="{{ route('customers.lines.destroy', [$customer, $line]) }}" method="POST" class="inline"> 
                                        @csrf 
                                        @method('DELETE') 
                                        <button 
                                            onclick="return confirm('{{ __('messages.confirm_delete_line') }}')" 
                                            class="text-red-600 hover:underline"> 
                                            {{ __('messages.delete') }} 
                                        </button> 
                                    </form> 
                                </td> 
                            </tr> 
                        @endforeach 
                    </tbody> 
                </table> 
            @else 
                <p class="text-gray-500 text-center py-10">{{ __('messages.no_lines_for_customer') }}</p> 
            @endif 
        </div> 
    </div> 
</x-app-layout>
