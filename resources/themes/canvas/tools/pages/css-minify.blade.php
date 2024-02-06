<x-tool-home-layout>
    {!! $tool->index_content !!}
    @if (setting('display_plan_homepage', 1) == 1)
        <x-plans-tools :plans="$plans ?? null" :properties="$properties" />
    @endif
    @if (setting('display_faq_homepage', 1) == 1)
        <x-faqs-tools :faqs="$faqs" />
    @endif
    <x-relevant-tools :relevant_tools="$relevant_tools" />

    @push('page_scripts')
        <script>
            const APP = function() {
                const editorInstance = document.querySelector('#cssInput')
                const attachEvents = function() {
                    if (document.getElementById('fileInput')) {
                        document.getElementById('fileInput').addEventListener('change', e => {
                            var file = document.getElementById("fileInput").files[0];
                            if (file.type != "text/css") {
                                ArtisanApp.toastError("{{ __('common.invalidFile') }}");
                                return;
                            }
                            APP.setFileContent(file);
                        });
                    };
                    @if (isset($results))
                        if (document.getElementById('btn-download-file')) {
                            document.getElementById('btn-download-file').addEventListener('click', () => {
                                ArtisanApp.downloadAsTxt('#minifiedCss', {
                                    filename: "{{ $tool->slug }}.css",
                                    fileMime: 'text/css'
                                });
                            })
                        }
                    @endif
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
</x-tool-home-layout>
