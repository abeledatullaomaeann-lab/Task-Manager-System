@props(['name', 'label', 'rows' => 3])

<div class="field">
    <label for="field-{{ $name }}">{{ $label }}</label>
    <textarea name="{{ $name }}" id="field-{{ $name }}" rows="{{ $rows }}"></textarea>
    <p class="field-error" data-error="{{ $name }}"></p>
</div>