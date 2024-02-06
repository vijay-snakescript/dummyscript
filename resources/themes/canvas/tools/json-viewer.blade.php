<x-application-tools-wrapper>
    <x-json-tools :tool="$tool" :title="__('tools.enterPasteJson')" :placeholder="__('tools.pasteJsonHere')" :label="__('tools.viewJson')" mode="text" mode2="tree" />
    @push('page_scripts')
        <script>
            const APP = function() {
                return {
                    updateContent: async function() {
                        const state = await JsonApp.validateEditor()
                        if (state) {
                            const InputJSON = JsonApp.editor().getText();
                            if (ArtisanApp.isJson(InputJSON)) {
                                JsonApp.initResult()
                                JsonApp.editor2().set(JSON.parse(InputJSON));
                            } else {
                                JsonApp.editor2().setText('{}');
                                ArtisanApp.toastError('{{ __('tools.invalidJson') }}')
                            }
                        } else {
                            document.querySelector('.json-tool-result').classList.add('d-none')
                        }
                    },
                }
            }();
        </script>
    @endpush
</x-application-tools-wrapper>
