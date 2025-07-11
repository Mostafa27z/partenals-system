<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="text-xl font-bold text-gray-800 leading-tight"> 
            📝 {{ __('messages.Change Log') }} 
        </h2> 
    </x-slot> 

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"> 
        <div class="bg-white p-6 rounded shadow"> 
            @if ($logs->count()) 
                <div class="overflow-x-auto"> 
                    <table class="min-w-full text-sm border border-gray-200 divide-y divide-gray-200"> 
                        <thead class="bg-gray-100 text-gray-700"> 
                            <tr class="text-right"> 
                                <th class="px-4 py-2 border">{{ __('messages.Model') }}</th> 
                                <th class="px-4 py-2 border">{{ __('messages.Record ID') }}</th> 
                                <th class="px-4 py-2 border">{{ __('messages.Field') }}</th> 
                                <th class="px-4 py-2 border text-red-700">{{ __('messages.Old Value') }}</th> 
                                <th class="px-4 py-2 border text-green-700">{{ __('messages.New Value') }}</th> 
                                <th class="px-4 py-2 border">{{ __('messages.User') }}</th> 
                                <th class="px-4 py-2 border">{{ __('messages.Date') }}</th> 
                            </tr> 
                        </thead> 
                        <tbody class="bg-white divide-y divide-gray-100"> 
                            @foreach ($logs as $log) 
                                <tr class="text-right hover:bg-gray-50"> 
                                    <td class="px-4 py-2 border"> 
                                        @switch(class_basename($log->model_type)) 
                                            @case('Customer') {{ __('messages.Customer') }} @break 
                                            @case('Line') {{ __('messages.Line') }} @break 
                                            @default {{ class_basename($log->model_type) }} 
                                        @endswitch 
                                    </td> 

                                    <td class="px-4 py-2 border"> 
                                        @if (class_basename($log->model_type) === 'Customer' && $log->model) 
                                            {{ $log->model->national_id ?? '---' }} 
                                        @elseif (class_basename($log->model_type) === 'Line' && $log->model) 
                                            {{ $log->model->phone_number ?? '---' }} 
                                        @else 
                                            {{ $log->model_id }} 
                                        @endif 
                                    </td> 

                                    <td class="px-4 py-2 border">{{ $log->field_name }}</td> 
                                    <td class="px-4 py-2 border text-red-700">{{ $log->old_value }}</td> 
                                    <td class="px-4 py-2 border text-green-700">{{ $log->new_value }}</td> 
                                    <td class="px-4 py-2 border">{{ $log->user?->name ?? __('messages.System') }}</td> 
                                    <td class="px-4 py-2 border">{{ $log->created_at->format('Y-m-d H:i') }}</td> 
                                </tr> 
                            @endforeach 
                        </tbody> 
                    </table> 
                </div> 

                <div class="mt-4"> 
                    {{ $logs->links() }} 
                </div> 
            @else 
                <p class="text-gray-600 text-center">{{ __('messages.No changes found.') }}</p> 
            @endif 
        </div> 
    </div> 
</x-app-layout>
