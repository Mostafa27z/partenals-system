<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            📊 ملخص الطلبات
            <a href="{{ route('requests.all') }}"
               class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm ml-4">
                📄 عرض جميع الطلبات
            </a>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-6 p-6">
        @php
            $icons = [
                'resell' => '🔁',
                'change_plan' => '📶',
                'change_chip' => '📱',
                'pause' => '⏸️',
                'resume' => '▶️',
                'change_date' => '📅',
                'change_distributor' => '🏢',
                'stop' => '⛔',
            ];
        @endphp

        <div class="grid md:grid-cols-2 gap-6">
            @foreach ($counts as $type => $data)
                <div class="bg-white shadow-sm rounded-xl p-5 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-xl font-semibold text-gray-700">
                            {{ $icons[$type] ?? '📄' }}
                            {{ __('طلب ' . str_replace('_', ' ', $type)) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            📅 اليوم: <span class="font-bold">{{ $data['today'] }}</span><br>
                            📦 الكل: <span class="font-bold">{{ $data['total'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
