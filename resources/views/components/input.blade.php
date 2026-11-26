@props(['name', 'label', 'type' => 'text'])

<div class="field">
    <label for="field-{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="field-{{ $name }}">
    <p class="field-error" data-error="{{ $name }}"></p>
</div>