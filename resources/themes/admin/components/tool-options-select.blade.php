@props([
    'field' => null,
    'tool' => null,
])
<div class="form-group mb-3">
    <label class="form-label" for="{{ $field['id'] }}">{{ $field['label'] }}</label>
    <select id="{{ $field['id'] }}" name="settings[{{ $field['id'] }}]"
        class="form-select {{ $field['classes'] ?? '' }}">
        @foreach ($field['options'] as $option)
            <option value="{{ $option['value'] }}" @if (isset($tool->settings->{$field['id']}) && $tool->settings->{$field['id']} == $option['value']) selected @endif>
                {{ $option['text'] }}</option>
        @endforeach
    </select>
</div>
