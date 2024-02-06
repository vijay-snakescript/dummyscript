<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div id="imageUploader" class="form-group">
                        <x-pdf-upload-wrapper :max-files="$tool->no_file_tool" :max-size="$tool->fs_tool" accept=".gif" input-name="files[]"
                            :file-title="__('tools.dropImageHereTitle')" pages="false" rotate="true" preview="true" sortable="true"
                            callbacks="{onFileChange: APP.onFileChange}">
                            <x-slot name="svg">
                                <i class="an an-image"></i>
                            </x-slot>
                            <div class="options-wrapper row d-none justify-content-center">
                                <div class="col-md-12 text-center">
                                    <h3>@lang('tools.toolOptions', ['name' => $tool->name])</h3>
                                </div>
                                <div class="col-auto">
                                    <select name="page_size" id="page_size" class="form-select">
                                        <option value="Fit">Fit (Same page size as image)</option>
                                        <option value="A4">A4 (297x210 mm)</option>
                                        <option value="Letter">US Letter (215x279.4 mm)</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <select name="page_orientation" id="page_orientation" class="form-select">
                                        <option value="auto">Automatic</option>
                                        <option value="portrait">Portrait</option>
                                        <option value="landscape">Landscape</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <select name="margin" id="margin" class="form-select">
                                        <option value="no-margin">No Margin</option>
                                        <option value="small-margin">Small Margin</option>
                                        <option value="big-margin">Big Margin</option>
                                    </select>
                                </div>
                                <div class="col-auto d-flex align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" name="merge_pages" id="merge_pages"
                                            class="form-check-input" value="1" checked>
                                        <label class="form-check-label" for="merge_pages">@lang('tools.mergeImagesInPDFFile')</label>
                                    </div>
                                </div>
                            </div>
                        </x-pdf-upload-wrapper>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.convertToPDF')
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
                                <th>@lang('common.fileName')</th>
                                <th width="200">@lang('common.size')</th>
                                <th width="150"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results['files'] as $file)
                                <tr>
                                    <td>
                                        <div class="mw-350 text-truncate fw-bold">{{ $file['filename'] }}</div>
                                    </td>
                                    <td>{{ formatSizeUnits($file['size']) }}</td>
                                    <td id="file-cursor">
                                        <button class="btn btn-outline-primary rounded-circle download-file-btn"
                                            data-bs-toggle="tooltip" title="@lang('common.download')" type="button"
                                            id="download-file-{{ $loop->index }}" data-url="{{ $file['url'] }}"
                                            data-filename="{{ $file['filename'] }}">
                                            <i class="an an-long-arrow-down"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    @if (count($results['files']) > 1)
                        <x-form class="no-app-loader download-all-btn d-inline-block" method="post" :route="route('tool.postAction', ['tool' => $tool->slug, 'action' => 'download-all'])">
                            <input type="hidden" name="process_id" value="{{ $results['process_id'] }}">
                            <x-download-form-button :text="__('tools.downloadAll')" />
                        </x-form>
                    @endif
                    <x-reload-button :link="route('tool.show', ['tool' => $tool->slug])" />
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
            const APP = function() {
                let isSelected = false;
                const optionsWrapper = document.querySelector('.options-wrapper')
                const attachEvents = function() {
                    document.querySelectorAll('.download-file-btn').forEach(button => {
                        button.addEventListener('click', e => {
                            const element = e.target.classList.contains('.download-file-btn') ? e
                                .target : e.target.closest('.download-file-btn')
                            console.log(element)
                            ArtisanApp.downloadFromUrl(element.dataset.url, element.dataset
                                .filename)
                        })
                    });
                };
                return {
                    init: function() {
                        attachEvents();
                    },
                    onFileChange: function(files) {
                        files.length > 0 ? optionsWrapper.classList.remove('d-none') : optionsWrapper.classList.add(
                            'd-none')
                    },
                }
            }();
            @if (isset($results))
                document.addEventListener("DOMContentLoaded", function(event) {
                    APP.init();
                });
            @endif
        </script>
    @endpush
</x-application-tools-wrapper>
