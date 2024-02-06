@props([
    'text' => null,
    'tooltip' => __('common.download'),
    'type' => 'submit',
])
<button type="{{ $type }}" data-bs-toggle="tooltip" title="{{ $tooltip }}"{!! $attributes->merge(['class' => 'btn btn-outline-primary rounded-' . ($text ? 'pill' : 'circle'), 'id']) !!}>
    <i class="an an-download"></i>
    @if ($text)
        <span>{{ $text }}</span>
    @endif
</button>
