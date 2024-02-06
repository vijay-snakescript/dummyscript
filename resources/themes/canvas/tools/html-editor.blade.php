<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12">
                    <div class="mh-300">
                        <x-textarea-input type="text" id="html-editor" class="editor" :placeholder="__('common.someText')" />
                    </div>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <div class="html-editor-result">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-page-wrapper :title="__('common.result')">
            <div class="result mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="custom-textarea p-3">
                            <x-textarea-input readonly id="content-editor" class="transparent" rows="8" />
                        </div>
                        <div class="json--results-actions result-copy mt-3 text-end">
                            <x-button class="btn btn-primary rounded-pill" type="button" id="saveToFile">
                                @lang('tools.saveAsHtml')
                            </x-button>
                            <x-copy-target target="content-editor" :text="__('common.copyToClipboard')" />
                        </div>
                    </div>
                </div>
            </div>
        </x-page-wrapper>
    </div>
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
        <script>
            const APP = function() {
                let editor = null;
                const attachEvents = function() {
                        document.getElementById('saveToFile').addEventListener('click', () => {
                            ArtisanApp.downloadAsTxt(document.getElementById('content-editor').value, {
                                isElement: false,
                                filename: '{{ $tool->slug . '.html' }}'
                            })
                        });
                    },
                    initEditor = function() {
                        document.querySelectorAll('.editor').forEach(elem => {
                            ClassicEditor.create(elem, {})
                                .then(instance => {
                                    instance.model.document.on('change:data', (evt, data) => {
                                        document.getElementById('content-editor').value = instance
                                            .getData()
                                    });
                                })
                                .catch(error => {
                                    console.log('error', error);
                                });
                        });
                    };
                return {
                    init: function() {
                        initEditor()
                        attachEvents()
                    },
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
