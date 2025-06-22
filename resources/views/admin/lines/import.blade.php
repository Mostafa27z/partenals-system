<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">📥 رفع ملف خطوط Excel</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('lines.import.process') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
            @csrf
            <div class="mb-4">
                <label class="block font-bold mb-1">ملف Excel</label>
                <input type="file" name="file" accept=".xlsx" required class="w-full border p-2 rounded">
            </div>
            <button class="btn btn-primary">⬆️ رفع الملف</button>
        </form>
    </div>
</x-app-layout>