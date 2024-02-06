@props([
    'text' => false,
])
<x-button type="button" class="btn-outline-primary rounded-circle copy-clipboard"
    data-clipboard-text="{{ $text }}" data-bs-toggle="tooltip" data-bs-placement="top"
    title="{{ __('common.copyToClipboard') }}" data-copied="{{ __('common.copied') }}">
    <i class="an an-copy"></i>
</x-button>
