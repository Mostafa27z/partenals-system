<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="text-xl font-bold">📦 {{ __('messages.manage_lines_for_sale') }}</h2> 
    </x-slot> 

    <div class="max-w-6xl mx-auto mt-6 bg-white p-6 rounded shadow"> 
        @if (session('success')) 
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow"> 
                {{ session('success') }} 
            </div> 
        @endif 

        <form method="POST" action="{{ route('lines.mark-for-sale') }}"> 
            @csrf 

            <div class="overflow-x-auto">
                <table class="min-w-full text-center border divide-y divide-gray-200 mb-4 text-sm sm:text-base"> 
                    <thead class="bg-gray-50"> 
                        <tr> 
                            <th class="px-4 py-2 whitespace-nowrap">📞 {{ __('messages.phone_number') }}</th> 
                            <th class="px-4 py-2 whitespace-nowrap">👤 {{ __('messages.customer') }}</th> 
                            <th class="px-4 py-2 whitespace-nowrap">💰 {{ __('messages.sale_price') }}</th> 
                            <th class="px-4 py-2 whitespace-nowrap">📍 {{ __('messages.for_sale') }}</th> 
                        </tr> 
                    </thead> 
                    <tbody class="divide-y divide-gray-100"> 
                        @foreach ($lines as $line) 
                            <tr class="hover:bg-gray-50"> 
                                <td class="px-4 py-2 whitespace-nowrap font-mono text-gray-700">{{ $line->phone_number }}</td> 
                                <td class="px-4 py-2 whitespace-nowrap text-gray-800">{{ $line->customer?->full_name ?? '-' }}</td> 

                                <td class="px-4 py-2 whitespace-nowrap"> 
                                    <input 
                                        type="number" step="0.01" name="lines[{{ $line->id }}][sale_price]" 
                                        value="{{ old("lines.$line->id.sale_price", $line->for_sale ? $line->sale_price : '') }}" 
                                        class="border border-gray-300 rounded px-2 py-1 w-24 text-center text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                                        placeholder="0.00"
                                    > 
                                </td> 

                                <td class="px-4 py-2 whitespace-nowrap"> 
                                    <input 
                                        type="checkbox" name="lines[{{ $line->id }}][selected]" value="1" 
                                        {{ $line->for_sale ? 'checked' : '' }} 
                                        class="w-5 h-5 cursor-pointer text-blue-600 focus:ring-blue-400"
                                    > 
                                </td> 
                            </tr> 
                        @endforeach 
                    </tbody> 
                </table> 
            </div>

            <div class="text-end"> 
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500"
                > 
                    💾 {{ __('messages.save_changes') }} 
                </button> 
            </div> 
        </form> 
    </div> 
</x-app-layout>
