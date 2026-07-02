@props(['icon' => 'document', 'label' => '', 'value' => 0, 'color' => 'blue'])

@php
    $palette = [
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'rose' => 'bg-rose-50 text-rose-600',
        'teal' => 'bg-teal-50 text-teal-600',
    ];
    $tile = $palette[$color] ?? $palette['blue'];

    $icons = [
        'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'paperclip' => 'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13',
        'database' => 'M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4-3.582 4-8 4-8-1.79-8-4zm0 0v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7',
        'folder' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
        'users' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-9a4 4 0 11-8 0 4 4 0 018 0zm6 4a3 3 0 11-6 0 3 3 0 016 0z',
        'shield' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
    ];
    $path = $icons[$icon] ?? $icons['document'];
@endphp

<div class="bg-white border border-gray-100 shadow-sm rounded-xl p-5 hover:shadow-md hover:-translate-y-0.5 transition duration-200">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-sm text-gray-500">{{ $label }}</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">{{ $value }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $tile }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
            </svg>
        </div>
    </div>
</div>
