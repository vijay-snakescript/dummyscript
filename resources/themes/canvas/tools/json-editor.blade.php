<x-application-tools-wrapper>
    <x-json-tools :tool="$tool" :title="__('tools.enterPasteJson')" :placeholder="__('tools.pasteJsonHere')" :label="__('tools.downloadJson')" mode="code" :mode2="false">
        <x-copy-callback callback="copyJsonResults" :text="__('common.copyToClipboard')" :svg="false" />
    </x-json-tools>
    @push('page_scripts')
        <script>
            const APP = function() {
                return {
                    updateContent: async function() {
                        const state = await JsonApp.validateEditor()
                        if (state) {
                            const InputJSON = JsonApp.editor().getText();
                            if (ArtisanApp.isJson(InputJSON)) {
                                ArtisanApp.downloadAsTxt(InputJSON, {
                                    isElement: false,
                                    filename: "{{ $tool->slug }}.json",
                                    fileMime: 'application/json'
                                });
                            } else {
                                ArtisanApp.toastError('{{ __('tools.invalidJson') }}')
                            }
                        }
                    }
                }
            }();
        </script>
    @endpush
</x-application-tools-wrapper>
