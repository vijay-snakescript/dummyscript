<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-tool-property-display class="mb-3" :tool="$tool" name="fs_tool" label="maxFileSizeLimit" :plans="true"
            upTo="upTo100KB" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <x-upload-wrapper :max-files="$tool->no_file_tool" :max-size="$tool->fs_tool" accept=".png"
                        input-name="images[]" :file-title="__('tools.dropImageHereTitle')" :file-label="__('tools.convertPngToJpgDesc')" />
                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.convertToJpg')
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
                                    <th width="100">@lang('common.size')</th>
                                    <th width="175"></th>
                                </tr>
                            </thead>
                            <tbody id="processing-files">

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 text-end">
                        <x-form class="d-none download-all-btn d-inline-block" metho="post" :route="route('tool.postAction', ['tool' => $tool->slug, 'action' => 'download-all'])">
                            <input type="hidden" name="process_id" value="{{ $results['process_id'] }}">
                            <x-download-form-button :text="__('tools.downloadAll')" />
                        </x-form>
                        <x-reload-button :link="route('tool.show', ['tool' => $tool->slug])" />
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
    @if (isset($results))
        @push('page_scripts')
            <script>
                const APP = function() {
                    let processed = 0;
                    const process_id = '{{ $results['process_id'] }}',
                        files = {!! collect($results['files'])->pick('original_filename', 'size', 'extension')->toJson() !!},
                        max_files = 20,
                        route = '{{ route('tool.postAction', ['tool' => $tool->slug, 'action' => 'process-file']) }}',
                        attachEvents = function(cursor) {
                            document.querySelectorAll('.download-file-btn').forEach(button => {
                                button.addEventListener('click', e => {
                                    ArtisanApp.downloadFromUrl(e.target.dataset.url, e.target.dataset.filename)
                                })
                            });
                        },
                        startConversion = async function(files, cursor) {
                                const file = files[cursor]
                                const file_ext = file.original_filename.split('.').pop();
                                processingNow(file, cursor)
                                await axios.post(route, {
                                    process_id: process_id,
                                    file: file.original_filename
                                }).then(resp => {
                                    updateProgress(cursor);
                                    showDownload(resp.data, cursor)
                                }).catch(err => {
                                    console.log(err)
                                })
                                cursor += 1
                                if (cursor < files.length && cursor <= max_files) {
                                    startConversion(files, cursor);
                                } else if (processed > 1) {
                                    document.querySelector('.download-all-btn').classList.remove('d-none')
                                }
                            },
                            processingNow = function(file, index) {
                                const element = document.querySelector('#processing-files');
                                const html = `<tr>
                                        <td>${index+1}</td>
                                        <td><div class="mw-350 text-truncate fw-bold">${file.original_filename}</div></td>
                                        <td id="new-size-${index}">${file.size.filesize(true)}</td>
                                        <td id="file-cursor-${index}"><div class="spinner-border" role="status"></div></td>
                                    </tr>`;

                                element.innerHTML += html;
                            },
                            updateProgress = function(cursor) {
                                var progress = (parseInt(cursor + 1) / files.length) * 100;
                                progress = Math.round(progress);

                                document.getElementById('conversion-progress').style.width = progress + '%'
                            },
                            showDownload = function(data, index) {
                                const button =
                                    `<button class="btn btn-outline-primary rounded-pill download-file-btn" type="button" id="download-${index}" data-url="${data.url}" data-filename="${data.filename}">
                                        {{ __('common.download') }}
                                    </button>`
                                if (data.success) {
                                    processed += 1;
                                }
                                document.getElementById('file-cursor-' + index).innerHTML = data.success ? button :
                                    '<span class="badge bg-danger">{{ __('common.failed') }}</span>'
                                document.getElementById('new-size-' + index).innerHTML = data.success ? data.size.filesize() :
                                    '-'
                                attachEvents()
                            };

                    return {
                        init: function() {
                            if (files.length > 0) {
                                startConversion(files, 0)
                            }
                        }
                    }
                }();
                document.addEventListener("DOMContentLoaded", function(event) {
                    APP.init();
                });
            </script>
        @endpush
    @endif
</x-application-tools-wrapper>
