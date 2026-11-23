@props(['href' => '#', 'icon', 'active' => false, 'count' => null])

<a href="{{ $href }}" class="nav-item {{ $active ? 'active' : '' }}">
    <i class="bi bi-{{ $icon }}"></i>
    <span>{{ $slot }}</span>
    @if ($count)
        <small>{{ $count }}</small>
    @endif
</a>