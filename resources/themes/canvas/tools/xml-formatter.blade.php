<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row custom-textarea-wrapper">
                <div class="col-md-12 mb-3">
                    <x-input-label>{{ __('tools.enterPasteXml') }}</x-input-label>
                    <x-textarea-input class="form-control transparent" rows="12" spellcheck="false" id="xmlInput">
                    </x-textarea-input>
                    <x-input-error :messages="$errors->get('xml')" class="mt-2" />
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="button" class="btn btn-outline-primary rounded-pill" id="formatXml">
                        {{ __('tools.formatXml') }}
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const attachEvents = function() {
                        document.getElementById('formatXml').addEventListener('click', event => {
                            const input = document.getElementById('xmlInput').value;
                            if (input == null || input == "") {
                                ArtisanApp.toastError("{{ __('tools.inputXmlRequired') }}");
                                return;
                            }
                            const output = formatXml(input);
                            document.getElementById('xmlInput').value = output;
                            ArtisanApp.toastSuccess("{{ __('tools.codeFormatMsg') }}");
                        });
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
                    };
                return {
                    init: function() {
                        attachEvents()
                    },
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
