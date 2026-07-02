@props(['icon' => 'document', 'label' => '', 'value' => 0, 'color' => 'purple'])

@php
    $icons = [
        'document' => 'fa-file-lines',
        'paperclip' => 'fa-paperclip',
        'database' => 'fa-database',
        'folder' => 'fa-folder-open',
        'users' => 'fa-users',
        'shield' => 'fa-shield-halved',
    ];
    $fa = $icons[$icon] ?? 'fa-file-lines';
@endphp

<div class="stat-card">
    <div class="stat-card__info">
        <div class="stat-card__label">{{ $label }}</div>
        <div class="stat-card__value">{{ $value }}</div>
    </div>
    <div class="stat-card__icon">
        <i class="fa-solid {{ $fa }}"></i>
    </div>
</div>
