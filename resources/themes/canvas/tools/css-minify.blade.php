<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-3">
                    <x-input-label>{{ __('tools.enterPasteCss') }}</x-input-label>
                    <div class="custom-textarea p-3">
                        <x-textarea-input class="transparent" rows="8" spellcheck="false" id="cssInput" name="css"
                            required>
                            {{ $results['css'] ?? '' }}</x-textarea-input>
                        <x-input-error :messages="$errors->get('css')" class="mt-2" />
                        <div class="file-input-container mt-2">
                            <x-input-file-button file-id="fileInput" accept=".css" />
                        </div>
                    </div>
                </div>
                <x-ad-slot :advertisement="get_advert_model('below-form')" />
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill" id="minify">
                        {{ __('tools.minify') }}
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
                    <div class="result tool-results">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <table class="table table-style">
                                    <thead>
                                        <th>@lang('tools.originalSize')</th>
                                        <th>@lang('tools.minifiedSize')</th>
                                        <th width="200">@lang('tools.saveSize')</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ formatSizeUnits($results['input_size']) }}</td>
                                            <td>{{ formatSizeUnits($results['output_size']) }}</td>
                                            <td>{{ formatSizeUnits($results['input_size'] - $results['output_size']) }}
                                                ({{ $results['save_size'] }} %)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="custom-textarea p-3">
                                    <x-textarea-input id="minifiedCss" class="transparent" readonly rows="8">
                                        {{ $results['content'] }}
                                    </x-textarea-input>
                                </div>
                                <div class="result-copy mt-3 text-end">
                                    <x-copy-target target="minifiedCss" :text="__('common.copyToClipboard')" :svg="false" />
                                    <x-download-form-button type="button" id="btn-download-file" :tooltip="__('tools.downloadCss')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const editorInstance = document.querySelector('#cssInput')
                const attachEvents = function() {
                    if (document.getElementById('fileInput')) {
                        document.getElementById('fileInput').addEventListener('change', e => {
                            var file = document.getElementById("fileInput").files[0];
                            if (file.type != "text/css") {
                                ArtisanApp.toastError("{{ __('common.invalidFile') }}");
                                return;
                            }
                            APP.setFileContent(file);
                        });
                    };
                    @if (isset($results))
                        if (document.getElementById('btn-download-file')) {
                            document.getElementById('btn-download-file').addEventListener('click', () => {
                                ArtisanApp.downloadAsTxt('#minifiedCss', {
                                    filename: "{{ $tool->slug }}.css",
                                    fileMime: 'text/css'
                                });
                            })
                        }
                    @endif
                };

                return {
                    init: function() {
                        attachEvents()
                    },
                    setFileContent: function(file) {
                        var reader = new FileReader();
                        reader.readAsText(file, "UTF-8");
                        reader.onload = function(evt) {
                            editorInstance.value = evt.target.result;
                        }
                        reader.onerror = function(evt) {
                            ArtisanApp.toastError("error reading file");
                        }
                    },
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
