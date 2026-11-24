@props(['id', 'title'])

<dialog id="{{ $id }}" class="modal">
    <div class="modal-head">
        <h3>{{ $title }}</h3>
        <button type="button" class="icon-btn" data-close-modal><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="modal-body">
        {{ $slot }}
    </div>
</dialog>