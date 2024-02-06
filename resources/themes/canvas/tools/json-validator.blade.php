<x-application-tools-wrapper>
    <x-json-tools :tool="$tool" :title="__('tools.enterPasteJson')" :placeholder="__('tools.pasteJsonHere')" :label="__('tools.validateJson')" :mode2="false" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const results = document.getElementById('json--results-container'),
                    actions = document.querySelector('.json--results-actions')
                return {
                    updateContent: async function() {
                        const state = await JsonApp.validateEditor()
                        const InputJSON = JsonApp.editor().getText();
                        message = null
                        if (ArtisanApp.isJson(InputJSON)) {
                            actions.classList.remove('d-none')
                            message = '<div class="alert alert-success">{{ __('tools.validJson') }}</div>';
                            ArtisanApp.toastSuccess('{{ __('tools.validJson') }}')
                        } else {
                            actions.classList.add('d-none')
                            message = '<div class="alert alert-danger">{{ __('tools.invalidJson') }}</div>';
                        }

                        if (message) {
                            results.innerHTML = message;
                        }

                        JsonApp.initResult()
                    }
                }
            }();
        </script>
    @endpush
</x-application-tools-wrapper>
