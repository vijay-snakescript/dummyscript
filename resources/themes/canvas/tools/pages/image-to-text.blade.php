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
            document.addEventListener("DOMContentLoaded", function(event) {
                ArtisanApp.initUpload(document.querySelector('.uploader-file-uploader'), {
                    dropOnBody: true,
                    maxFiles: 1,
                    fileExtensions: ".png|.jpg|.jpeg",
                    maxSize: {{ $tool->fs_tool }},
                }, {
                    extensionsError: "{{ __('admin.fileTypeNotSupported') }}",
                    sizeError: "{{ __('admin.maxFileSizeError', ['size' => $tool->fs_tool]) }}",
                    filesError: "{{ __('admin.maxFileLimitError') }}",
                });
            });

            document.addEventListener("DOMContentLoaded", function(event) {
                if (document.querySelector('#printResult')) {
                    document.querySelector('#printResult').addEventListener('click', () => {
                        ArtisanApp.printResult(document.querySelector('.printable-result'), {
                            title: "{{ $tool->name }}"
                        })
                    })
                }
            });
        </script>
    @endpush
</x-tool-home-layout>
