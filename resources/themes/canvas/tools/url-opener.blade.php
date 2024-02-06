<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <x-input-label>{{ __('tools.enterDomainLimitLabel', ['count' => 50]) }}</x-input-label>
                        <x-textarea-input id="input-urls" class="form-control" :placeholder="__('tools.enterDomainLimitPlaceholder')" rows="12" spellcheck="false" />
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="button" class="btn btn-outline-primary rounded-pill" id="open-url">
                        {{ __('tools.openUrls') }}
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const openUrls = function() {
                        var urls = document.getElementById("input-urls").value;
                        if (urls == '') {
                            ArtisanApp.toastError('{{ __('tools.invalidUrl') }}');
                            return;
                        }
                        urls.split('\n').forEach(url => {
                            if (isValid(url)) {
                                let link = document.createElement('a');
                                link.href = url;
                                link.target = '_blank';
                                link.click();
                            }
                        });
                    },
                    isValid = function(str) {
                        regexp =
                            /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
                        return regexp.test(str)
                    },
                    attachEvents = function() {
                        document.getElementById('open-url').addEventListener('click', e => {
                            openUrls()
                        })
                    };

                return {
                    init: function() {
                        attachEvents();
                    }
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
