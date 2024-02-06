<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div id="imageUploader" class="form-group">
                        <x-pdf-upload-wrapper max-files="1" :max-size="$tool->fs_tool" accept=".pdf" input-name="files[]"
                            pages="true" rotate="false" preview="true" sortable="false" />
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.generatePdf')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4">
                    <table class="table table-style">
                        <thead>
                            <tr>
                                <th width="75">#</th>
                                <th>@lang('common.fileName')</th>
                                <th width="200">@lang('common.size')</th>
                                <th width="150"></th>
                            </tr>
                        </thead>
                        <tbody id="processing-files">
                            @foreach ($results['files'] as $file)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="mw-350 text-truncate fw-bold">{{ $file['filename'] }}</div>
                                    </td>
                                    <td>{{ formatSizeUnits($file['size']) }}</td>
                                    <td id="file-cursor">
                                        <button class="btn btn-outline-primary rounded-circle download-file-btn"
                                            data-bs-toggle="tooltip" title="@lang('common.download')" type="button"
                                            id="download-file" data-url="{{ $file['url'] }}"
                                            data-filename="{{ $file['filename'] }}">
                                            <i class="an an-long-arrow-down"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script src="{{ url('themes/default/js/pdf/pdf.min.js') }}"></script>
        @vite(['resources/themes/canvas/assets/js/app-pdf.js'])
        <script>
            pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ url('themes/default/js/pdf/pdf.worker.min.js') }}';
            @if (isset($results))
                const APP = function() {
                    let isSelected = false;
                    const attachEvents = function() {
                        document.querySelectorAll('.download-file-btn').forEach(button => {
                            button.addEventListener('click', e => {
                                const element = e.target.classList.contains('.download-file-btn') ? e
                                    .target : e.target.closest('.download-file-btn')
                                ArtisanApp.downloadFromUrl(element.dataset.url, element.dataset
                                    .filename)
                            })
                        });
                    };
                    return {
                        init: function() {
                            attachEvents();
                        },
                    }
                }();
                document.addEventListener("DOMContentLoaded", function(event) {
                    APP.init();
                });
            @endif
        </script>
    @endpush
</x-application-tools-wrapper>
