@props([
    'accept' => '.jpg,.jpeg,.gif,.png',
    'maxSize' => null,
    'maxFiles' => 1,
    'fileTitle' => null,
    'fileLabel' => __('admin.selectFileOrDrag'),
    'buttonLabel' => __('admin.selectAFile'),
    'id' => 'file-uploader',
    'dropOnBody' => true,
    'inputName' => 'files',
    'onSelectFile' => null,
])
<div class="artisan-uploader bg-light rounded uploader-{{ $id }}">
    <input id="{{ $id }}" type="file" name="{{ $inputName }}"
        accept="{{ $accept }}"{{ $maxFiles > 1 ? ' multiple' : '' }} />
    <div class="bg-priamary p-2 add-more d-none position-absolute">
        <label class="btn btn-primary btn-pill" for="{{ $id }}">+</label>
    </div>
    <label for="{{ $id }}" class="file-drag{{ $slot->isEmpty() ? '' : ' pb-1' }}">
        <div class="uploader-wrapper">
            @if (!empty($svg))
                {{ $svg }}
            @else
                <i class="an an-upload-image"></i>
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
            ArtisanApp.initUpload(document.querySelector('.uploader-{{ $id }}'), {
                dropOnBody: {{ $dropOnBody ? 'true' : 'false' }},
                maxFiles: {{ $maxFiles }},
                fileExtensions: "{{ Str::replace(',', '|', $accept) }}",
                @if (!empty($maxSize))
                    maxSize: {{ $maxSize }},
                @endif
                @if (!empty($onSelectFile))
                    fileSelectedCallback: '{{ $onSelectFile }}',
                @endif
            }, {
                extensionsError: "{{ __('admin.fileTypeNotSupported') }}",
                sizeError: "{{ __('admin.maxFileSizeError', ['size' => $maxSize]) }}",
                filesError: "{{ __('admin.maxFileLimitError') }}",
            });
        });
    </script>
@endpush
