<x-tool-home-layout>
    {!! $tool->index_content !!}
    @if (setting('display_plan_homepage', 1) == 1)
        <x-plans-tools :plans="$plans ?? null" :properties="$properties" />
    @endif
    @if (setting('display_faq_homepage', 1) == 1)
        <x-faqs-tools :faqs="$faqs" />
    @endif
    <x-relevant-tools :relevant_tools="$relevant_tools" />
    @push('page_scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
                ArtisanApp.initUpload(document.querySelector('.uploader-file-uploader'), {
                    dropOnBody: true,
                    maxFiles: {{ $tool->no_file_tool }},
                    fileExtensions: "{{ Str::replace(',', '|', '.png,.jpeg,.jpg') }}",
                    maxSize: {{ $tool->fs_tool }},
                }, {
                    extensionsError: "{{ __('admin.fileTypeNotSupported') }}",
                    sizeError: "{{ __('admin.maxFileSizeError', ['size' => $tool->fs_tool]) }}",
                    filesError: "{{ __('admin.maxFileLimitError') }}",
                });
            });
            @if (isset($results))
                const APP = function() {
                    let processed = 0;
                    const process_id = '{{ $results['process_id'] }}',
                        files = {!! collect($results['files'])->pick('original_filename', 'size', 'extension')->toJson() !!},
                        max_files = 20,
                        route = '{{ route('tool.postAction', ['tool' => $tool->slug, 'action' => 'process-file']) }}',
                        attachEvents = function(cursor) {
                            document.querySelectorAll('.download-file-btn').forEach(button => {
                                button.addEventListener('click', e => {
                                    ArtisanApp.downloadFromUrl(e.target.dataset.url, e.target.dataset
                                        .filename)
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
                                        <td>${file.size.filesize(true)} <span id="new-size-${index}"></span></td>
                                        <td id="compress-ratio-${index}"></td>
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
                                    `<button class="btn btn-outline-primary rounded-circle download-file-btn" data-bs-toggle="tooltip" title="@lang('common.download')" type="button" id="download-${index}" data-url="${data.url}" data-filename="${data.filename}">
                                            <i class="an an-long-arrow-down"></i>
                                        </button>`
                                if (data.success) {
                                    processed += 1;
                                }
                                document.getElementById('file-cursor-' + index).innerHTML = data.success ? button :
                                    '<span class="badge bg-danger">{{ __('common.failed') }}</span>'

                                if (data.success) {
                                    document.getElementById('new-size-' + index).innerHTML =
                                        ` {{ __('common.toSmall') }} ${data.size.filesize(true)}`
                                    document.getElementById('compress-ratio-' + index).innerHTML =
                                        `${data.compression_ratio.round(2)}%`
                                    var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                                        '[data-bs-toggle="tooltip"]'))
                                    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                                        return new Tooltip(tooltipTriggerEl)
                                    })
                                }
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
            @endif
        </script>
    @endpush
</x-tool-home-layout>
