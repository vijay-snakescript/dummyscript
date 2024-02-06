@props([
    'svg' => true,
    'target' => false,
    'text' => false,
    'class' => 'btn-outline-primary',
])

<button type="button" data-clipboard-target="#{{ $target }}" data-copied="{{ __('common.copied') }}"
    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('common.copyToClipboard') }}" {!! $attributes->merge(['class' => $class . ' btn copy-clipboard rounded-' . ($text ? 'pill' : 'circle')]) !!}>
    @if ($svg)
        <i class="an an-copy"></i>
    @endif
    @if ($text)
        <span>{{ $text }}</span>
    @endif
</button>
