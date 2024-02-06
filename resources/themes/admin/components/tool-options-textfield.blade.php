@props([
    'field' => null,
    'tool' => null,
])
<div class="form-group mb-3">
    <label class="form-label" for="{{ $field['id'] }}">{{ $field['label'] }}</label>
    <input class="form-control {{ $errors->has($field['id']) ? 'is-invalid' : '' }}{{ $field['classes'] ?? '' }}"
        id="{{ $field['id'] }}" name="settings[{{ $field['id'] }}]" value="{{ $tool->settings->{$field['id']} ?? '' }}"
        type="{{ $field['type'] }}" {!! is_array($field['dependant']) && count($field['dependant']) === 2
            ? 'data-conditional-name="' . $field['dependant'][0] . '" data-conditional-value="' . $field['dependant'][1] . '"'
            : '' !!} placeholder="{{ $field['placeholder'] ?? '' }}">
    <x-input-error :messages="$errors->get($field['id'])" />
</div>
