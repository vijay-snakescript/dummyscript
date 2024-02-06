@props([
    'svg' => true,
    'callback' => false,
    'text' => false,
    'class' => 'btn-outline-primary',
])

<button type="button" data-callback="{{ $callback }}" data-copied="{{ __('common.copied') }}" data-bs-toggle="tooltip"
    data-bs-placement="top" title="{{ __('common.copyToClipboard') }}" {!! $attributes->merge(['class' => $class . ' btn copy-clipboard rounded-' . ($text ? 'pill' : 'circle')]) !!}>
    @if ($svg)
        <i class="an an-copy"></i>
    @endif
    @if ($text)
        <span>{{ $text }}</span>
    @endif
</button>
