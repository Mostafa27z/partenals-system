<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 sm:gap-6 w-full">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight text-center sm:text-right">
                {{ __('messages.all_lines') }}
            </h2>

            <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4">
                <a href="{{ route('lines.for-sale') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm sm:text-base text-center">
                    📦 {{ __('messages.for_sale') }}
                </a>

                <a href="{{ route('lines.trashed') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow text-sm sm:text-base text-center">
                    🗑️ {{ __('messages.trashed') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 break-words max-w-full text-sm sm:text-base" dir="rtl">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow break-words max-w-full text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search Form --}}
        <div class="mb-4 flex flex-wrap gap-4 items-center break-words max-w-full text-sm sm:text-base">
            <a href="{{ route('lines.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow break-words max-w-full text-sm sm:text-base">
                ➕ {{ __('messages.new_line') }}
            </a>
            <a href="{{ route('lines.import.form') }}" class="btn btn-secondary break-words max-w-full text-sm sm:text-base">
                📥 {{ __('messages.upload_excel') }}
            </a>
            <a href="{{ route('lines.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow break-words max-w-full text-sm sm:text-base">
                ⬇️ {{ __('messages.export_all') }}
            </a>

            <form method="GET" action="{{ route('lines.all') }}" class="flex flex-wrap gap-4 mt-2 sm:mt-0 break-words max-w-full text-sm sm:text-base">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="{{ __('messages.phone_number') }}" class="input input-bordered w-full sm:w-40 break-words max-w-full text-sm sm:text-base" />
                <input type="text" name="nid" value="{{ request('nid') }}" placeholder="{{ __('messages.national_id') }}" class="input input-bordered w-full sm:w-40 break-words max-w-full text-sm sm:text-base" />
                <input type="text" name="provider" value="{{ request('provider') }}" placeholder="{{ __('messages.provider') }}" class="input input-bordered w-full sm:w-40 break-words max-w-full text-sm sm:text-base" />
                <input type="text" name="distributor" value="{{ request('distributor') }}" placeholder="{{ __('messages.distributor') }}" class="input input-bordered w-full sm:w-40 break-words max-w-full text-sm sm:text-base" />
                <select name="plan_id" class="input input-bordered w-full sm:w-40 break-words max-w-full text-sm sm:text-base">
                    <option value="">-- {{ __('messages.plan') }} --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-primary break-words max-w-full text-sm sm:text-base">🔍 {{ __('messages.search') }}</button>
            </form>
        </div>

        {{-- Export Form --}}
        <div class="bg-white overflow-x-auto w-full rounded-lg shadow border border-gray-200 text-sm sm:text-base">
            <form method="POST" action="{{ route('lines.export.selected') }}">
                @csrf

                <table class="min-w-full divide-y divide-gray-200 text-center break-words max-w-full text-sm sm:text-base min-w-full text-sm text-gray-800 whitespace-nowrap" dir="rtl">
                    <thead class="bg-gray-50 break-words max-w-full text-sm sm:text-base">
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th class="px-4 py-2 break-words max-w-full text-sm sm:text-base">{{ __('messages.phone_number') }}</th>
                            <th class="px-4 py-2 break-words max-w-full text-sm sm:text-base">{{ __('messages.national_id') }}</th>
                            <th class="px-4 py-2 break-words max-w-full text-sm sm:text-base">{{ __('messages.customer_name') }}</th>
                            <th class="px-4 py-2 break-words max-w-full text-sm sm:text-base">{{ __('messages.status') }}</th>
                            <th class="px-4 py-2 break-words max-w-full text-sm sm:text-base">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 break-words max-w-full text-sm sm:text-base">
                        @foreach($lines as $line)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_lines[]" value="{{ $line->id }}" class="line-checkbox break-words max-w-full text-sm sm:text-base">
                                </td>
                                <td class="px-4 py-2 break-words max-w-full text-sm sm:text-base">{{ $line->phone_number }}</td>
                                <td class="px-4 py-2 break-words max-w-full text-sm sm:text-base">{{ $line->customer->national_id ?? '-' }}</td>
                                <td class="px-4 py-2 break-words max-w-full text-sm sm:text-base">{{ $line->customer->full_name ?? '-' }}</td>
                                <td class="px-4 py-2 break-words max-w-full text-sm sm:text-base">
                                    {{ $line->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                </td>
                                <td class="px-4 py-2 space-x-2 flex justify-center gap-2 flex-wrap break-words max-w-full text-sm sm:text-base">
                                    <button type="button" class="text-blue-600 hover:underline break-words max-w-full text-sm sm:text-base" onclick="toggleDetails({{ $line->id }})">
                                        👁️ {{ __('messages.view') }}
                                    </button>
                                    <a href="{{ route('lines.edit', $line->id) }}" class="text-yellow-600 hover:underline break-words max-w-full text-sm sm:text-base">
                                        ✏️ {{ __('messages.edit') }}
                                    </a>
                                    <button type="button" class="text-red-600 hover:underline break-words max-w-full text-sm sm:text-base" onclick="confirmDelete({{ $line->id }})">
                                        🗑 {{ __('messages.delete') }}
                                    </button>
                                    @if($line->plan)
                                        <a href="{{ route('invoices.create', $line) }}" class="text-green-600 hover:underline break-words max-w-full text-sm sm:text-base">
                                            💳 {{ __('messages.pay') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            <!-- Details -->
                            <tr id="line-details-{{ $line->id }}" style="display: none;" class="bg-gray-100 text-lg break-words max-w-full text-sm sm:text-base">
                                <td colspan="6" class="p-4 text-start break-words max-w-full text-sm sm:text-base">
                                    <div><strong>{{ __('messages.provider') }}:</strong> {{ $line->provider }}</div>
                                    <div><strong>{{ __('messages.line_type') }}:</strong> {{ $line->line_type === 'prepaid' ? __('messages.prepaid') : __('messages.postpaid') }}</div>
                                    <div><strong>{{ __('messages.plan') }}:</strong> {{ $line->plan->name ?? '-' }}</div>
                                    <div><strong>{{ __('messages.distributor') }}:</strong> {{ $line->distributor ?? '-' }}</div>
                                    <div><strong>GCode:</strong> {{ $line->gcode }}</div>
                                    <div><strong>{{ __('messages.attached_at') }}:</strong>{{ \Carbon\Carbon::parse($line->attached_at)->format('Y-m-d') }} </div>
                                    <div><strong>{{ __('messages.last_invoice') }}:</strong> {{ \Carbon\Carbon::parse($line->last_invoice_date)->format('Y-m-d') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @push('scripts')
                <script>
                    function toggleDetails(id) {
                        const row = document.getElementById('line-details-' + id);
                        if (row.style.display === 'none') {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                </script>
                @endpush

                <div class="mt-4 px-4 text-end break-words max-w-full text-sm sm:text-base">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow break-words max-w-full text-sm sm:text-base">
                        ⬇️ {{ __('messages.export_selected') }}
                    </button>
                </div>
            </form>

            <div class="mt-4 px-4 break-words max-w-full text-sm sm:text-base">
                {{ $lines->links() }}
            </div>
        </div>

        @php
            $forms = [
                ['route' => 'requests.stop.import', 'label' => __('messages.import_stop_requests')],
                ['route' => 'requests.resell.import', 'label' => __('messages.import_resell_requests')],
                ['route' => 'requests.change-plan.import', 'label' => __('messages.import_change_plan_requests')],
                ['route' => 'requests.change-chip.import', 'label' => __('messages.import_change_chip_requests')],
                ['route' => 'requests.change-distributor.import', 'label' => __('messages.import_change_distributor_requests')],
                ['route' => 'requests.change-date.import', 'label' => __('messages.import_change_date_requests')],
                ['route' => 'requests.resume.import', 'label' => __('messages.import_resume_requests')],
                ['route' => 'requests.pause.import', 'label' => __('messages.import_pause_requests')],
            ];
        @endphp

        @foreach ($forms as $form)
            <form action="{{ route($form['route']) }}" method="POST" enctype="multipart/form-data"
                  class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 bg-white p-4 rounded shadow w-full">
                @csrf
                <input type="file" name="file" accept=".xlsx" required
                       class="border border-gray-300 rounded p-2 w-full sm:w-auto text-sm sm:text-base" />

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full sm:w-auto text-sm sm:text-base text-center">
                    {{ $form['label'] }}
                </button>
            </form>
        @endforeach

        @if($lines->count() === 1)
            @php
                $line = $lines->first();
            @endphp

            <div class="mt-6 p-4 bg-blue-50 rounded shadow break-words max-w-full text-sm sm:text-base">
                <h3 class="font-bold mb-2 break-words max-w-full text-sm sm:text-base">📱 {{ __('messages.phone') }}: {{ $line->phone_number }}</h3>

                <form method="GET" onsubmit="return redirectToCreateRequest(event)">
                    <label for="request-type" class="block mb-1 font-medium break-words max-w-full text-sm sm:text-base">{{ __('messages.select_request_type') }}:</label>
                    <select id="request-type" class="input input-bordered w-full max-w-xs break-words max-w-full text-sm sm:text-base" required>
                        <option value="">-- {{ __('messages.select_type') }} --</option>
                        <option value="resell">{{ __('messages.resell') }}</option>
                        <option value="change-plan">{{ __('messages.change_plan') }}</option>
                        <option value="change-chip">{{ __('messages.change_chip') }}</option>
                        <option value="pause">{{ __('messages.pause') }}</option>
                        <option value="resume">{{ __('messages.resume') }}</option>
                        <option value="change-date">{{ __('messages.change_date') }}</option>
                        <option value="change-distributor">{{ __('messages.change_distributor') }}</option>
                        <option value="stop-line">{{ __('messages.stop_line') }}</option>
                    </select>

                    <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded break-words max-w-full text-sm sm:text-base">
                        ➕ {{ __('messages.create_request') }}
                    </button>
                </form>
            </div>

            @push('scripts')
            <script>
                function redirectToCreateRequest(event) {
                    event.preventDefault();
                    const type = document.getElementById('request-type').value;
                    if (!type) {
                        alert("❌ {{ __('messages.select_request_type_first') }}");
                        return false;
                    }

                    const lineId = {{ $line->id }};
                    const baseUrl = {
                        'resell': '/admin/requests/resell/' + lineId,
                        'change-plan': '/admin/requests/change-plan/' + lineId,
                        'change-chip': '/admin/requests/change-chip/' + lineId,
                        'pause': '/admin/requests/pause/' + lineId,
                        'resume': '/admin/requests/resume/' + lineId + '/create',
                        'change-date': '/admin/requests/change-date/' + lineId,
                        'change-distributor': '/admin/requests/change-distributor/' + lineId,
                        'stop-line': '/admin/requests/stop/' + lineId,
                    };

                    if (baseUrl[type]) {
                        window.location.href = baseUrl[type];
                    } else {
                        alert("❌ {{ __('messages.request_type_not_supported') }}");
                    }
                }
            </script>
            @endpush
        @endif

        {{-- Hidden Delete Form --}}
        <form method="POST" id="delete-form" style="">
            @csrf
            @method('DELETE')
        </form>

        @push('scripts')
            <script>
                document.getElementById('select-all').addEventListener('change', function () {
                    document.querySelectorAll('.line-checkbox').forEach(cb => cb.checked = this.checked);
                });

                function confirmDelete(lineId) {
                    if (confirm('{{ __('messages.delete_line_confirmation') }}')) {
                        const form = document.getElementById('delete-form');
                        form.action = `/admin/lines/${lineId}`;
                        form.submit();
                    }
                }
            </script>
        @endpush
    </div>
</x-app-layout>