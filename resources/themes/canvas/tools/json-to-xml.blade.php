<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-5">
                    <div class="row">
                        <div class="col-md-12">
                            <x-input-label>@lang('tools.loadFromUrl')</x-input-label>
                        </div>
                        <div class="col">
                            <x-text-input type="url" class="me-3" name="url" id="urlUpload" :placeholder="__('tools.enterOrPasteUrl')" />
                        </div>
                        <div class="col-auto">
                            <x-input-file-button file-id="fileInput" accept=".json" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <x-input-label>{{ __('tools.enterPasteJson') }}</x-input-label>
                    <div class="form-group mh-400" id="jsoneditor"></div>
                    <x-input-error :messages="$errors->get('json')" class="mt-2" />
                </div>
                <x-ad-slot />
                <div class="col-md-12 text-end">
                    <x-button type="button" class="btn btn-outline-primary rounded-pill" id="convertToXml">
                        {{ __('tools.jsonToXml') }}
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <div class="json-tool-result d-none">
        <x-ad-slot />
        <x-page-wrapper :title="__('common.result')">
            <div class="result mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div id="json--results-container">
                            <div class="box-shadow custom-textarea bg-transparent tabbar mb-3">
                                <textarea aria-labelledby="{{ __('common.result') }}" id="json--results-textarea" class="form-control transparent" readonly rows="12" spellcheck="false"></textarea>
                            </div>
                            <div class="result-copy mt-3 text-end">
                                <x-button class="btn btn-primary rounded-pill" type="button"
                                    onclick="ArtisanApp.downloadAsTxt('#json--results-textarea', {filename: '{{ $tool->slug . '.txt' }}'})">
                                    @lang('tools.saveAsTxt')
                                </x-button>
                                <x-copy-target target="json--results-textarea" :text="__('common.copyToClipboard')" />
                            </div>
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
                let editorInstance = null;
                const jsonEditor = function() {
                        editorInstance = new JSONEditor(document.getElementById("jsoneditor"), {
                            mode: 'code',
                            onError: function(err) {
                                ArtisanApp.toastError(err.toString());
                            },
                        })
                    },
                    attachEvents = function() {
                        document.getElementById('fileInput').addEventListener('change', e => {
                            var file = document.getElementById("fileInput").files[0];
                            if (file.type != "application/json") {
                                ArtisanApp.toastError("{{ __('common.invalidFile') }}");
                                return;
                            }
                            APP.setFileContent(file);
                        });
                        document.getElementById('urlUpload').addEventListener('input', e => {
                            urlContent(e.target);
                        })
                        document.getElementById('convertToXml').addEventListener('click', event => {
                            convertToXml()
                        })
                    },
                    urlContent = function(element) {
                        var url = element.value;
                        var RegExp =
                            /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
                        if (RegExp.test(url)) {
                            fetch(url)
                                .then(res => res.json())
                                .then(out => {
                                    editorInstance.set(out)
                                })
                                .catch(err => {
                                    throw err
                                });
                        }
                    },
                    formatXml = function(xml) {
                        var reg = /(>)\s*(<)(\/*)/g;
                        var wsexp = / *(.*) +\n/g;
                        var contexp = /(<.+>)(.+\n)/g;
                        xml = xml.replace(reg, '$1\n$2$3').replace(wsexp, '$1\n').replace(contexp, '$1\n$2');
                        var pad = 0;
                        var formatted = '';
                        var lines = xml.split('\n');
                        var indent = 0;
                        var lastType = 'other';
                        var transitions = {
                            'single->single': 0,
                            'single->closing': -1,
                            'single->opening': 0,
                            'single->other': 0,
                            'closing->single': 0,
                            'closing->closing': -1,
                            'closing->opening': 0,
                            'closing->other': 0,
                            'opening->single': 1,
                            'opening->closing': 0,
                            'opening->opening': 1,
                            'opening->other': 1,
                            'other->single': 0,
                            'other->closing': -1,
                            'other->opening': 0,
                            'other->other': 0
                        };

                        for (var i = 0; i < lines.length; i++) {
                            var ln = lines[i];

                            if (ln.match(/\s*<\?xml/)) {
                                formatted += ln + "\n";
                                continue;
                            }

                            var single = Boolean(ln.match(/<.+\/>/));
                            var closing = Boolean(ln.match(/<\/.+>/));
                            var opening = Boolean(ln.match(/<[^!].*>/));
                            var type = single ? 'single' : closing ? 'closing' : opening ? 'opening' : 'other';
                            var fromTo = lastType + '->' + type;
                            lastType = type;
                            var padding = '';

                            indent += transitions[fromTo];
                            for (var j = 0; j < indent; j++) {
                                padding += '\t';
                            }
                            if (fromTo == 'opening->closing')
                                formatted = formatted.substr(0, formatted.length - 1) + ln +
                                '\n';
                            else
                                formatted += padding + ln + '\n';
                        }

                        return formatted;
                    },
                    convertToXml = async function() {
                        const state = await APP.validateEditor()
                        if (state) {
                            var InputJSON = editorInstance.get();
                            var x2js = new X2JS();
                            var output = x2js.json2xml_str(InputJSON);
                            output = formatXml(output);
                            var formated_xml = '<' + '?xml version="1.0" encoding="UTF-8" ?>\n<root>\n' + output +
                                '</root>';
                            document.getElementById('json--results-textarea').value = formated_xml
                            document.querySelector('.json-tool-result').classList.remove('d-none')

                            document.querySelector(".json-tool-result").scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        } else {
                            document.querySelector('.json-tool-result').classList.add('d-none')
                        }
                    };

                return {
                    init: function() {
                        jsonEditor()
                        attachEvents()
                    },
                    setFileContent: function(file) {
                        var reader = new FileReader();
                        reader.readAsText(file, "UTF-8");
                        reader.onload = function(evt) {
                            editorInstance.setText(evt.target.result);
                        }
                        reader.onerror = function(evt) {
                            ArtisanApp.toastError("error reading file");
                        }
                    },
                    validateEditor: async function() {
                        let state = false
                        await editorInstance.validate()
                            .then(res => {
                                if (res.length != 0) {
                                    res.forEach(r => {
                                        ArtisanApp.toastError(r.message)
                                    });
                                } else {
                                    state = true
                                }
                            })
                        return state
                    }
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
