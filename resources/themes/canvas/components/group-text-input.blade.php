@props([
    'disabled' => false,
])
<x-group-input>
    <x-text-input type="text" name="string" {!! $attributes->merge(['class' => $error ? 'is-invalid' : '']) !!} required autofocus />
    <x-button type="submit" class="btn-primary">
        @lang('common.generate')
    </x-button>
</x-group-input>
