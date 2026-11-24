@props(['type' => 'success'])

<div class="alert alert-{{ $type }}">
    <i class="bi bi-check-circle"></i> {{ $slot }}
</div>