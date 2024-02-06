<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <div class="panel-left-generator box-shadow py-5">
            <div class="row">
                <div class="col-md-12">
                    <x-form method="post" :route="route('tool.handle', $tool->slug)">
                        <div class="panel-left-radio">
                            <div class="panel-left">
                                <div class="controller mb-3">
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input type="radio" id="encode" class="radio-checkbox-input"
                                                name="type" value="1"
                                                @if (isset($type) && $type == '1') checked @endif />
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.encode')</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input class="radio-checkbox-input" id="decode" type="radio"
                                                name="type" value="2" autocomplete="off"
                                                @if (isset($type) && $type == '2') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.decode')</span>
                                            </span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('type')" />
                                    <x-button type="submit" class="btn btn-primary rounded-pill">
                                        @lang('common.generate')
                                    </x-button>
                                    @if (!isset($results))
                                        <x-input-file-button :svg="false"
                                            class="btn btn-primary rounded-pill d-block upload-btn mt-2"
                                            file-id="fileInput" accept=".txt" />
                                    @else
                                        <x-button class="btn-primary rounded-pill mt-2" type="button"
                                            onclick="ArtisanApp.downloadAsTxt('#save-as-file', {filename: '{{ $tool->slug . '.txt' }}'})">
                                            @lang('tools.saveAsTxt')
                                        </x-button>
                                        <x-copy-target class="btn-primary mt-2" :svg="false" target="save-as-file"
                                            :text="__('common.copyToClipboard')" />
                                    @endif
                                </div>
                                <div class="textarea d-flex flex-column justify-content-between">
                                    <x-textarea-input type="text" name="string" class="form-control h-50 mb-3"
                                        :placeholder="__('common.someText')" id="textarea" required autofocus>
                                        {{ $results['original_text'] ?? old('string') }}
                                    </x-textarea-input>
                                    <x-input-error :messages="$errors->get('string')" class="mt-2" />
                                    <x-textarea-input type="text" class="form-control h-50" id="save-as-file">
                                        {!! $results['converted_text'] ?? '' !!}
                                    </x-textarea-input>
                                </div>
                            </div>
                        </div>
                    </x-form>
                </div>
            </div>
        </div>
        <x-ad-slot class="mt-3" :advertisement="get_advert_model('below-form')" />
    </x-tool-wrapper>
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const editorInstance = document.querySelector('#textarea')
                const attachEvents = function() {
                    if (document.getElementById('fileInput')) {
                        document.getElementById('fileInput').addEventListener('change', e => {
                            var file = document.getElementById("fileInput").files[0];
                            if (file.type != "text/plain") {
                                ArtisanApp.toastError("{{ __('common.invalidFile') }}");
                                return;
                            }
                            APP.setFileContent(file);
                        });
                    }
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
