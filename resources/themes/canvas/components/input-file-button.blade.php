@props([
    'label' => __('common.uploadFile'),
    'class' => 'btn btn-primary rounded-pill upload-btn',
    'accept' => '.txt',
    'onchange' => null,
    'svg' => true,
    'fileId' => 'file',
])
<div class="{{ $class }}">
    @if ($svg)
        <i class="an an-attch-clip"></i>
    @endif
    {{ $label }} <input type="file" name="file" accept="{{ $accept }}" onchange="{{ $onchange }}"
        id="{{ $fileId }}" />
</div>
