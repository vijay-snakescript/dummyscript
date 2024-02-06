@props([
    'field' => null,
])
<div class="form-group mb-3">
    <label class="form-label" for="{{ $field['id'] }}">{{ $field['label'] }}</label>
    <textarea class="form-control" id="{{ $field['id'] }}" name="{{ $field['id'] }}" value=""
        type="{{ $field['type'] }}"></textarea>
</div>
