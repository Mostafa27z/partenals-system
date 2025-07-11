<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800">{{ __('messages.upload_excel_lines') }}</h2> 
    </x-slot> 

    <div class="py-6 max-w-md sm:max-w-lg md:max-w-3xl mx-auto px-4"> 
        @if(session('success')) 
            <div class="bg-green-100 text-green-800 p-4 rounded mb-6 shadow">
                {{ session('success') }}
            </div> 
        @endif 

        @if($errors->any()) 
            <div class="bg-red-100 text-red-800 p-4 rounded mb-6 shadow"> 
                <ul class="list-disc list-inside space-y-1"> 
                    @foreach($errors->all() as $e) 
                        <li>{{ $e }}</li> 
                    @endforeach 
                </ul> 
            </div> 
        @endif 

        <form action="{{ route('lines.import.process') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow max-w-full"> 
            @csrf 
            <div class="mb-5"> 
                <label for="file" class="block font-semibold mb-2 text-gray-700">{{ __('messages.excel_file') }}</label> 
                <input 
                    type="file" 
                    name="file" 
                    id="file" 
                    accept=".xlsx" 
                    required 
                    class="w-full border border-gray-300 rounded px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                > 
            </div> 
            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-150"
            >
                {{ __('messages.upload_file') }}
            </button> 
        </form> 
    </div> 
</x-app-layout>
