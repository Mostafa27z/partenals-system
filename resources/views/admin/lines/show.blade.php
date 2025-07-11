<x-app-layout>  
    <x-slot name="header">  
        <h2 class="font-semibold text-xl text-gray-800">@lang('Line Details')</h2>  
    </x-slot>  
  
    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8" dir="ltr">  
        <div class="bg-white p-6 rounded shadow space-y-6">  
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-gray-700">  
                <li><strong>@lang('ID'):</strong> {{ $line->id }}</li>  
                <li><strong>@lang('Customer ID'):</strong> {{ $line->customer_id ?? '-' }}</li>  
                <li><strong>@lang('Attached Date'):</strong> {{ $line->attached_at ?? '-' }}</li>  
                <li><strong>@lang('Phone Number'):</strong> {{ $line->phone_number }}</li>  
                <li><strong>@lang('Secondary Phone'):</strong> {{ $line->second_phone ?? '-' }}</li>  
                <li><strong>@lang('Provider'):</strong> {{ $line->provider }}</li>  
                <li><strong>@lang('Status'):</strong> {{ $line->status ?? '-' }}</li>  
                <li><strong>@lang('Offer Name'):</strong> {{ $line->offer_name ?? '-' }}</li>  
                <li><strong>@lang('Branch Name'):</strong> {{ $line->branch_name ?? '-' }}</li>  
                <li><strong>@lang('Employee Name'):</strong> {{ $line->employee_name ?? '-' }}</li>  
                <li><strong>@lang('GCode'):</strong> {{ $line->gcode ?? '-' }}</li>  
                <li><strong>@lang('Distributor'):</strong> {{ $line->distributor ?? '-' }}</li>  
                <li><strong>@lang('Line Type'):</strong> {{ $line->line_type === 'prepaid' ? __('Prepaid') : __('Postpaid') }}</li>  
                <li><strong>@lang('Plan'):</strong> {{ $line->plan->name ?? __('Not specified') }}</li>  
                <li><strong>@lang('Package'):</strong> {{ $line->package ?? '-' }}</li>  
                <li><strong>@lang('Last Invoice Date'):</strong> {{ $line->last_invoice_date ?? '-' }}</li>  
                <li><strong>@lang('Notes'):</strong> {{ $line->notes ?? '-' }}</li>  
                <li><strong>@lang('Added By'):</strong> {{ $line->addedBy->name ?? __('Unknown') }}</li>   
                <li><strong>@lang('Created At'):</strong> {{ $line->created_at->format('Y-m-d H:i') }}</li>  
                <li><strong>@lang('Last Updated'):</strong> {{ $line->updated_at->format('Y-m-d H:i') }}</li>  
                <li><strong>@lang('For Sale?'):</strong> {{ $line->for_sale ? __('Yes') : __('No') }}</li>  
                <li><strong>@lang('Sale Price'):</strong> {{ $line->sale_price ?? '-' }}</li>  
                <li><strong>@lang('Deleted At'):</strong> {{ $line->deleted_at ? $line->deleted_at->format('Y-m-d H:i') : __('Not deleted') }}</li>  
            </ul>  
 
            @if($line->customer)  
                <div class="pt-4">  
                    <a href="{{ route('customers.show', $line->customer) }}"  
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow inline-block transition">  
                        🔙 @lang('Back to Customer Details')  
                    </a>  
                </div>  
            @endif  
        </div>  
    </div>  
</x-app-layout>
