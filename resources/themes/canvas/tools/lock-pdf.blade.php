<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div id="pdfUploader" class="form-group">
                        <x-pdf-upload-wrapper :max-files="$tool->no_file_tool" :max-size="$tool->fs_tool" accept=".pdf" input-name="files[]"
                            pages="false" rotate="false" preview="true" sortable="true"
                            callbacks="{onFileChange: APP.onFileChange}">
                            <div class="password-wrapper d-none">
                                <div class="password-input">
                                    <input class="form-control form-control-lg password-field" name="password"
                                        id="password" type="password" placeholder="Password"
                                        aria-label="@lang('admin.enterPassword')">
                                    <button class="btn-input-icon show-password" id="togglePassword"
                                        type="button"></button>
                                </div>
                            </div>
                        </x-pdf-upload-wrapper>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <input type="hidden" name="action" value="lock" />
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.lockPDF')
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
                                            id="download-file" data-url="{{ $file['url'] }}"
                                            data-filename="{{ $file['filename'] }}">
                                            <i class="an an-long-arrow-down"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12 text-end">
                            @if (count($results['files']) > 1)
                                <x-form class="no-app-loader download-all-btn d-inline-block" method="post"
                                    :route="route('tool.postAction', [
                                        'tool' => $tool->slug,
                                        'action' => 'download-all',
                                    ])">
                                    <input type="hidden" name="process_id" value="{{ $results['process_id'] }}">
                                    <x-download-form-button :text="__('tools.downloadAll')" />
                                </x-form>
                            @endif
                            <x-reload-button :link="route('tool.show', ['tool' => $tool->slug])" />
                        </div>
                    </div>
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
                const passwordWrapper = document.querySelector('.password-wrapper')
                let state = false;
                const attachEvents = function() {
                    @if (isset($results))
                        document.querySelectorAll('.download-file-btn').forEach(button => {
                            button.addEventListener('click', e => {
                                const element = e.target.classList.contains('.download-file-btn') ? e
                                    .target : e.target.closest('.download-file-btn')
                                ArtisanApp.downloadFromUrl(element.dataset.url, element.dataset
                                    .filename)
                            })
                        });
                    @endif
                    document.getElementById('togglePassword').addEventListener('click', e => {
                        e.target.classList.toggle('show-password')
                        e.target.classList.toggle('hide-password')
                        const input = e.target.parentElement.querySelector('.password-field')
                        state ? input.setAttribute("type", "password") : input.setAttribute("type", "text");
                        state = !state
                    })
                };
                return {
                    init: function() {
                        attachEvents();
                    },
                    onFileChange: function(files) {
                        files.length > 0 ? passwordWrapper.classList.remove('d-none') : passwordWrapper.classList.add(
                            'd-none')
                    },
                    isFileHasPassword: function(file) {
                        ArtisanApp.toastError("{{ __('tools.fileAlreadyLockedMsg') }}".replace(':file', file.name))
                        return true;
                    }
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
