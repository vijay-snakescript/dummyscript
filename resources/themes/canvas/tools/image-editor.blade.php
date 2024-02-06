<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div id="imageUploader" class="form-group">
                        <x-upload-wrapper max-files="1" :max-size="$tool->fs_tool" accept=".png,.jpg,jpeg" input-name="image"
                            :file-title="__('tools.dropImageHereTitle')" :file-label="false" on-select-file="onFileSelect" />
                    </div>
                    <div id="imgEditable" class="d-none"></div>
                </div>
            </div>
        </x-form>
        <x-ad-slot :advertisement="get_advert_model('below-form')" />
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4">

                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                let isSelected = false;
                const app = document.querySelector('body')

                return {
                    init: function() {
                        app.classList.add('tool-initialized')
                    },
                    onFileSelect: function(event) {
                        console.log(event)
                        if (isSelected) {
                            return;
                        }
                        const file = event[0];
                        const src = URL.createObjectURL(file)
                        var image = new Image();
                        image.src = src;
                        image.id = 'image-editable';
                        image.className = 'img-fluid';
                        image.onload = function() {

                        };

                        document.getElementById('imgEditable').appendChild(image)
                        document.getElementById('imageUploader').classList.add('d-none')
                        document.getElementById('imgEditable').classList.remove('d-none')
                        isSelected = true
                    }
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
            window.onFileSelect = APP.onFileSelect
        </script>
    @endpush
</x-application-tools-wrapper>
