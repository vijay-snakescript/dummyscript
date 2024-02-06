@props([
    'text' => null,
    'tooltip' => __('common.convertMoreFiles'),
    'link' => null,
    'class' => null,
])
@if ($link)
    <a href="{{ $link }}" data-bs-toggle="tooltip" title="{{ $tooltip }}"{!! $attributes->merge(['class' => 'btn btn-outline-primary rounded-' . ($text ? 'pill' : 'circle')]) !!}>
        <i class="an an-reload"></i>
        @if ($text)
            <span>{{ $text }}</span>
        @endif
    </a>
@endif
