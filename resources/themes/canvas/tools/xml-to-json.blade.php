<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-12">
                            <x-input-label>@lang('tools.loadFromUrl')</x-input-label>
                        </div>
                        <div class="col">
                            <x-text-input type="url" class="me-3" name="url" id="urlUpload" :placeholder="__('tools.enterOrPasteUrl')" />
                        </div>
                        <div class="col-auto">
                            <x-input-file-button file-id="fileInput" accept=".xml" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <x-input-label>{{ __('tools.enterPasteXml') }}</x-input-label>
                    <x-textarea-input id="xml-textarea" class="form-control transparent" rows="12"
                        spellcheck="false">
                    </x-textarea-input>
                    <x-input-error :messages="$errors->get('xml')" class="mt-2" />
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="button" class="btn btn-outline-primary rounded-pill" id="convertToJson">
                        {{ __('tools.xmlToJson') }}
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <div class="json-tool-result d-none">
        <x-page-wrapper :title="__('common.result')">
            <div class="result mt-4">
                <x-ad-slot :advertisement="get_advert_model('above-result')" />
                <div class="row">
                    <div class="col-md-12">
                        <div id="json--results-container"></div>
                        <div class="json--results-actions result-copy mt-3 text-end">
                            <x-button class="btn btn-primary rounded-pill" type="button" id="saveToFile">
                                @lang('tools.saveAsTxt')
                            </x-button>
                            <x-copy-callback callback="copyJsonResults" :text="__('common.copyToClipboard')" />
                        </div>
                    </div>
                </div>
            </div>
        </x-page-wrapper>
    </div>
    <x-tool-content :tool="$tool" />
    @push('page_header')
        @vite(['resources/themes/canvas/assets/sass/jsoneditor.scss', 'resources/themes/canvas/assets/js/jsoneditor.js'])
    @endpush
    @push('page_scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/x2js/1.2.0/xml2json.min.js"
            integrity="sha512-HX+/SvM7094YZEKOCtG9EyjRYvK8dKlFhdYAnVCGNxMkA59BZNSZTZrqdDlLXp0O6/NjDb1uKnmutUeuzHb3iQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            const APP = function() {
                let editor = null;
                const attachEvents = function() {
                        document.getElementById('convertToJson').addEventListener('click', event => {
                            convertToJson()
                        });
                        document.getElementById('saveToFile').addEventListener('click', () => {
                            ArtisanApp.downloadAsTxt(editor.getText(), {
                                isElement: false,
                                filename: '{{ $tool->slug . '.txt' }}'
                            })
                        });
                        document.getElementById('fileInput').addEventListener('change', e => {
                            var file = document.getElementById("fileInput").files[0];
                            if (file.type != "text/xml" || file.type == "application/xml") {
                                ArtisanApp.toastError("{{ __('common.invalidFile') }}");
                                return;
                            }
                            APP.setFileContent(file);
                        });
                        document.getElementById('urlUpload').addEventListener('input', e => {
                            urlContent(e.target);
                        })
                    },
                    initEditor = function() {
                        editor = new JSONEditor(document.getElementById("json--results-container"), {
                            mode: 'code',
                            modes: ['code', 'form', 'text', 'tree', 'view'],
                            onError: function(err) {
                                ArtisanApp.toastError(err.toString());
                            },
                        })
                    },
                    convertToJson = async function() {
                            var inputXml = document.getElementById('xml-textarea').value;
                            if (inputXml == null) {
                                ArtisanApp.toastError('{{ __('tools.inputXmlRequired') }}')
                                return
                            }
                            var x2js = new X2JS();
                            var output = x2js.xml_str2json(inputXml);
                            if (output == null) {
                                ArtisanApp.toastError('{{ __('tools.invalidXml') }}')
                                return
                            }
                            editor.frame.querySelector('.jsoneditor-outer').classList.add('mh-400')
                            editor.set(output);
                            document.querySelector('.json-tool-result').classList.remove('d-none')
                            document.querySelector(".json-tool-result").scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        },
                        urlContent = function(element) {
                            var url = element.value;
                            var RegExp =
                                /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
                            if (RegExp.test(url)) {
                                fetch(url)
                                    .then(res => res.json())
                                    .then(out => {
                                        document.getElementById('xml-textarea').value = out;
                                    })
                                    .catch(err => {
                                        throw err
                                    });
                            }
                        };
                return {
                    init: function() {
                        initEditor()
                        attachEvents()
                    },
                    getData: function() {
                        return editor.getText();
                    },
                    setFileContent: function(file) {
                        var reader = new FileReader();
                        reader.readAsText(file, "UTF-8");
                        reader.onload = function(evt) {
                            document.getElementById('xml-textarea').value = evt.target.result;
                        }
                        reader.onerror = function(evt) {
                            ArtisanApp.toastError("error reading file");
                        }
                    },
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
                window.copyJsonResults = APP.getData
            });
        </script>
    @endpush
</x-application-tools-wrapper>
