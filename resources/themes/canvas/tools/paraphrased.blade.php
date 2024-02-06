<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <x-tool-property-display :tool="$tool" name="wc_tool" label="wordCountLimit" :plans="true"
                upTo="upTo30k">
            </x-tool-property-display>
            <div class="row mb-4">
                <div class="col-md-12 mt-2 mb-3">
                    <div class="form-group">
                        <x-textarea-input type="text" name="string" class="form-control" rows="8"
                            :placeholder="__('common.someText')" id="textarea" required autofocus contenteditable="true">
                            {{ $results['original_text'] ?? old('string') }}
                        </x-textarea-input>
                    </div>
                    <x-input-error :messages="$errors->get('string')" class="mt-2" />
                </div>
                  <div class="col-md-12 d-flex">
                    <div class="me-auto">
                        <x-input-file-button />
                    </div>
                    <div  class="ms-auto">
                    <x-button type="submit" class="btn-outline-primary rounded-pill text-end">
                        @lang('tools.paraphrase')
                    </x-button>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-shadow tabbar mb-3 custom-textarea">
                                <x-textarea-input id="rewrite-result" class="form-control transparent" rows="12">
                                    {{ $results['converted_text'] }}
                                </x-textarea-input>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-copy-target target="rewrite-result" />
                                    <x-download-form-button type="button"
                                        onclick="ArtisanApp.downloadAsTxt('#rewrite-result', {filename: '{{ $tool->slug . '.txt' }}'})"
                                        :tooltip="__('tools.saveAsTxt')" />
                                    <x-print-button
                                        onclick="ArtisanApp.printResult(document.querySelector('#rewrite-result'), {title: '{{ $tool->name }}'})"
                                        :tooltip="__('tools.printResult')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                let editorInstance = document.getElementById('textarea');
                const attachEvents = function() {
                        document.getElementById('file').addEventListener('change', e => {
                            var file = document.getElementById("file").files[0];
                            if (file.type != "text/plain") {
                                ArtisanApp.toastError("{{ __('common.invalidFile') }}");
                                return;
                            }
                            APP.setFileContent(file);
                        });
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
