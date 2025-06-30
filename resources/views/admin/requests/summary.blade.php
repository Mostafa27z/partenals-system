<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800"><a href="{{ route('requests.all') }}"
                           class="inline-block mt-4 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                            📄 عرض الطلبات
                        </a> 📊 ملخص الطلبات</h2>

    </x-slot>

    <div class="max-w-7xl mx-auto mt-6 p-6">
        @php
            $typesWithLine = ['resell', 'change_plan', 'change_chip', 'pause', 'resume', 'change_date', 'change_distributor'];
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
            @foreach ($counts as $type => $count)
                <div class="bg-white shadow-sm rounded-xl p-5 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-xl font-semibold text-gray-700">
                            {{ $icons[$type] ?? '📄' }}
                            {{ __('طلب ' . str_replace('_', ' ', $type)) }}
                        </div>
                        <div class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                            {{ $count }} طلب
                        </div>
                    </div>

                    @if (in_array($type, $typesWithLine))
                        <form method="GET"
                              action="{{ route('requests.' . str_replace('_', '-', $type) . '.create', ['line' => 'REPLACE_LINE_ID']) }}"
                              onsubmit="return handleRedirect(event, '{{ route('requests.' . str_replace('_', '-', $type) . '.create', ['line' => 'LINE_ID_PLACEHOLDER']) }}')">
                            <div class="space-y-2">
                                <input type="text" placeholder="🔍 ابحث عن رقم الخط..."
                                       class="w-full border-gray-300 rounded px-3 py-2"
                                       oninput="filterLines(this, '{{ $type }}')">

                                <select class="w-full border-gray-300 rounded px-3 py-2" id="line-select-{{ $type }}" required size="5">
                                    @foreach ($lines as $line)
                                        <option value="{{ $line->id }}">
                                            {{ $line->phone_number }} - {{ $line->customer?->full_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="text-end">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                        ➕ إنشاء الطلب
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script>
            function filterLines(input, type) {
                const select = document.getElementById(`line-select-${type}`);
                const filter = input.value.toLowerCase();

                for (let i = 0; i < select.options.length; i++) {
                    const option = select.options[i];
                    const text = option.text.toLowerCase();
                    option.style.display = text.includes(filter) ? '' : 'none';
                }
            }

            function handleRedirect(event, baseUrl) {
                event.preventDefault();
                const form = event.target;
                const type = form.querySelector('select').id.replace('line-select-', '');
                const select = document.getElementById(`line-select-${type}`);
                const lineId = select.value;

                if (!lineId) {
                    alert("❌ الرجاء اختيار رقم الخط أولاً.");
                    return false;
                }

                const url = baseUrl.replace('LINE_ID_PLACEHOLDER', lineId);
                window.location.href = url;
            }
        </script>
    @endpush
</x-app-layout>
