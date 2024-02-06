@props([
    'disabled' => false,
])

<button {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'btn']) !!}>
    {{ $slot }}
</button>
