@props(['name', 'label', 'type' => 'text'])

<div class="field">
    <label for="field-{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="field-{{ $name }}" value="{{ old($name) }}">
    @error($name)
        <p class="field-error">{{ $message }}</p>
    @enderror
</div>