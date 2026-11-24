@props(['label', 'value', 'hint', 'icon', 'color' => '#5b9a6b'])

<div class="card stat">
    <div class="stat-top">
        <span>{{ $label }}</span>
        <span class="stat-icon" style="background: {{ $color }}22; color: {{ $color }}">
            <i class="bi bi-{{ $icon }}"></i>
        </span>
    </div>
    <div class="stat-value">{{ $value }}</div>
    <div class="stat-hint">{{ $hint }}</div>
</div>