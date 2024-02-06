@props([
    'disabled' => false,
    'error' => false,
])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $error ? 'is-invalid form-control' : 'form-control']) !!}>
