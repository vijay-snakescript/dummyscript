@props([
    'disabled' => false,
    'error' => false,
])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $error ? 'is-invalid form-control' : 'form-control']) !!}>{{ $slot }}</textarea>
