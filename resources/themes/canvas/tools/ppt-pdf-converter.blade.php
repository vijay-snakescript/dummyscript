<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div id="imageUploader" class="form-group">
                        <x-pdf-upload-wrapper max-files="2" :max-size="$tool->fs_tool" accept=".ppt,.pptx" input-name="files[]"
                           :file-title="__('tools.dropPptDocumentHereTitle')" pages="false" rotate="false" preview="false" sortable="false" />
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress" style="height: 3px;">
                            <div id="conversion-progress" class="progress-bar bg-success" role="progressbar"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
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
                    <div class="col-md-12 text-end">
                        @if(count($results['files']) > 1)
                        <x-form class="no-app-loader download-all-btn d-inline-block" method="post" :route="route('tool.postAction', ['tool' => $tool->slug, 'action' => 'download-all'])">
                            <input type="hidden" name="process_id" value="{{ $results['process_id'] }}">
                            <x-download-form-button :text="__('tools.downloadAll')" />
                        </x-form>
                        @endif
                        <x-reload-button :link="route('tool.show', ['tool' => $tool->slug])" />
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    @push('page_scripts')
        <script src="{{ url('themes/default/js/pdf/pdf.min.js') }}"></script>
        @vite(['resources/themes/canvas/assets/js/app-pdf.js'])
        <script>
            pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ url('themes/default/js/pdf/pdf.worker.min.js') }}';
            const APP = function() {
                let isSelected = false;
                @if (isset($results))
                    const process_id = '{{ $results['process_id'] }}',
                        attachEvents = function() {
                            document.querySelectorAll('.download-file-btn').forEach(button => {
                                button.addEventListener('click', e => {
                                    const element = e.target.classList.contains('.download-file-btn')? e.target : e.target.closest('.download-file-btn')
                                    ArtisanApp.downloadFromUrl(element.dataset.url, element.dataset
                                        .filename)
                                })
                            });
                        };
                @endif
                return {
                    init: function() {
                        @if (isset($results))
                            attachEvents();
                        @endif
                    },
                    onFileSelect: function(event) {
                        console.log(event)
                    },

                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
