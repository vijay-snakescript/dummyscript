@props([
    'route' => null,
    'method' => 'post',
])
<form method="{{ $method }}" action="{{ $route }}" {{ $attributes->merge() }}>
    @csrf
    {{ $slot }}
</form>
