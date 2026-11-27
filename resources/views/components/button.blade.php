@props(['variant' => 'primary', 'type' => 'button', 'icon' => null])

@php
    $classes = match ($variant) {
        'ghost' => 'btn-ghost',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)<i class="bi bi-{{ $icon }}"></i>@endif
    {{ $slot }}
</button>