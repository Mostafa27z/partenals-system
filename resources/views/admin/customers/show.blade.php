<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.Customer Details') }}
        </h2> 
    </x-slot> 

    <div class="py-6" dir="rtl"> 
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> 
            <div class="bg-white p-6 rounded shadow space-y-4 text-sm sm:text-base"> 
                <ul class="space-y-2 text-gray-800"> 
                    <li><strong>ID:</strong> {{ $customer->id }}</li> 
                    <li><strong>{{ __('messages.Full Name') }}:</strong> {{ $customer->full_name }}</li> 
                    <li><strong>{{ __('messages.National ID') }}:</strong> {{ $customer->national_id }}</li> 
                    <li><strong>{{ __('messages.Birth Date') }}:</strong> {{ $customer->birth_date ?? '-' }}</li> 
                    <li><strong>{{ __('messages.Email') }}:</strong> {{ $customer->email ?? '-' }}</li> 
                    <li><strong>{{ __('messages.Address') }}:</strong> {{ $customer->address ?? '-' }}</li> 
                    <li><strong>{{ __('messages.Created At') }}:</strong> {{ $customer->created_at->format('Y-m-d H:i') }}</li> 
                    <li><strong>{{ __('messages.Updated At') }}:</strong> {{ $customer->updated_at->format('Y-m-d H:i') }}</li> 
                    <li><strong>{{ __('messages.Deleted At') }}:</strong> {{ $customer->deleted_at ? $customer->deleted_at->format('Y-m-d H:i') : __('messages.Not Deleted') }}</li> 
                </ul> 

                @if($customer->lines->count()) 
                    <div class="pt-6"> 
                        <h3 class="font-semibold text-lg mb-2">{{ __('messages.Linked Lines') }}:</h3> 
                        <ul class="space-y-1 list-disc list-inside text-gray-700"> 
                            @foreach($customer->lines as $line) 
                                <li> 
                                    <span class="font-medium">{{ $line->phone_number }}</span> - 
                                    {{ $line->provider }} 
                                    ({{ $line->line_type === 'prepaid' ? __('messages.Prepaid') : __('messages.Postpaid') }}) 
                                    <a href="{{ route('lines.show', $line) }}" class="text-blue-600 hover:underline ml-2">{{ __('messages.View') }}</a> 
                                </li> 
                            @endforeach 
                        </ul> 
                    </div> 
                @endif 
            </div> 
        </div> 
    </div> 
</x-app-layout>
