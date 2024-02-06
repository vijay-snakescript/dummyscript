@props([
    'accept' => '.pdf',
    'maxSize' => null,
    'maxFiles' => 1,
    'fileTitle' => __('tools.dropPDFHereTitle'),
    'fileLabel' => __('admin.selectFileOrDrag'),
    'buttonLabel' => __('admin.selectAFile'),
    'id' => 'file-uploader',
    'dropOnBody' => true,
    'inputName' => 'files',
    'onSelectFile' => null,
    'pages' => 'false',
    'preview' => 'true',
    'rotate' => 'true',
    'sortable' => 'false',
    'callbacks' => '{}',
    'equalHeight' => false,
    'allowProtectedFiles' => 'true',
])
<div class="artisan-uploader bg-light rounded uploader-{{ $id }}{{ !$equalHeight ? '' : ' h-100' }}">
    <input id="{{ $id }}" type="file" name="{{ $inputName }}"
        accept="{{ $accept }}"{{ $maxFiles > 1 ? ' multiple' : '' }} />
    <input type="hidden" class="pdf_file__data" name="fileData">
    <div class="bg-priamary p-2 add-more d-none">
        <div class="d-flex justify-content-between align-items-center">
            <label class="btn btn-primary btn-pill" for="{{ $id }}">+</label>
            <div class="ml-auto pdf___more-actions">

            </div>
        </div>
    </div>
    <label for="{{ $id }}" class="position-relative file-drag{{ $slot->isEmpty() ? '' : ' pb-1' }}">
        <div class="file-loader">
            <div class="spinner-grow" role="status">
                <span class="sr-only">@lang('common.loading')</span>
            </div>
        </div>
        <div class="uploader-wrapper">
            @if (!empty($svg))
                {{ $svg }}
            @else
                <i class="an an-overview"></i>
            @endif
            @if ($fileTitle)
                <h3>{{ $fileTitle }}</h3>
            @endif
            @if (!empty($accept))
                <div class="uploader-extensions mb-3">
                    @foreach (explode(',', $accept) as $ext)
                        <span class="badge text-uppercase">{{ $ext }}</span>
                    @endforeach
                </div>
            @endif
            <p>{{ $fileLabel }}</p>
            <span class="btn btn-dark file-upload-btn">{{ $buttonLabel }}</span>
        </div>
        <div class="files-grid"></div>
    </label>
    @if (!$slot->isEmpty())
        <div class="uploader-after p-3">
            {{ $slot }}
        </div>
    @endif
</div>
@push('page_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            PDFApp.initPDFUpload(document.querySelector('.uploader-{{ $id }}'), {
                    dropOnBody: {{ $dropOnBody ? 'true' : 'false' }},
                    maxFiles: {{ $maxFiles }},
                    previewPages: {{ $pages }},
                    allowPreview: {{ $preview }},
                    allowRotate: {{ $rotate }},
                    isSortable: {{ $sortable }},
                    allowProtectedFiles: {{ $allowProtectedFiles }},
                    fileExtensions: "{{ Str::replace(',', '|', $accept) }}",
                    @if (!empty($maxSize))
                        maxSize: {{ $maxSize }},
                    @endif
                }, {
                    extensionsError: "{{ __('admin.fileTypeNotSupported') }}",
                    sizeError: "{{ __('admin.maxFileSizeError', ['size' => $maxSize]) }}",
                    filesError: "{{ __('admin.maxFileLimitError') }}",
                    tooManyInvalidAttempts: "{{ __('tools.tooManyInvalidAttempts') }}",
                    fileNotSupported: "{{ __('tools.fileNotSupported') }}",
                },
                @if ($callbacks)
                    {{ $callbacks }}
                @endif
            );
        });
    </script>
@endpush
