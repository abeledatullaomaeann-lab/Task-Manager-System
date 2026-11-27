@props(['name', 'label'])

<div class="field">
    <label for="field-{{ $name }}">{{ $label }}</label>
    <select name="{{ $name }}" id="field-{{ $name }}">{{ $slot }}</select>
    <p class="field-error" data-error="{{ $name }}"></p>
</div>