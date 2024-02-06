<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <x-textarea-input type="text" name="string" id="editor" class="editor" :placeholder="__('common.someText')">
                            {{ old('string') }}
                        </x-textarea-input>
                        <x-input-error :messages="$errors->get('string')" class="mt-2" />
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-download-form-button id="downloadImage" type="button" :text="__('common.download')" />
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script>
            const APP = function() {
                const initEditor = function() {
                        ClassicEditor.create(document.querySelector('.editor'), {
                                toolbar: ['heading', '|', 'blockQuote', 'bold', 'italic', 'link', '|', 'bulletedList',
                                    'numberedList', 'insertTable', '|', 'imageUpload', 'image', 'alignment', '|',
                                    'fontFamily', 'fontSize', 'fontColor', 'FontBackgroundColor',
                                ],
                                simpleUpload: {
                                    uploadUrl: '{{ route('uploader.upload') }}',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                },
                            })
                            .then(editor => {})
                            .catch(error => {
                                console.log('error', error);
                            });
                    },
                    renderText = function() {
                        html2canvas(document.querySelector('.ck-editor__editable'), {
                            backgroundColor: 'rgba(0,0,0,0)',
                        }).then((canvas) => {
                            var a = document.createElement('a');
                            a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
                            a.download = '{{ $tool->slug }}.jpg';
                            a.click();
                        });
                    },
                    attachEvents = function() {
                        document.getElementById('downloadImage').addEventListener('click', e => {
                            document.getElementById('downloadImage').disabled = true
                            renderText()
                            document.getElementById('downloadImage').disabled = false
                        })
                    };

                return {
                    init: function() {
                        initEditor();
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
