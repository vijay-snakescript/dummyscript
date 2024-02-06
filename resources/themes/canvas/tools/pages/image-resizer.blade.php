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
        <script></script>
        <script>
            const APP = function() {
                const heightField = document.getElementById('height'),
                    widthField = document.getElementById('width'),
                    percentageField = document.getElementById('percentageField'),
                    resizeField = document.getElementById('resize'),
                    rotateField = document.getElementById('rotate'),
                    flipHField = document.getElementById('flip_horizontal'),
                    flipVField = document.getElementById('flip_vertical'),
                    //
                    maxDimensions = document.getElementById('maxDimensions'),
                    percentSize = document.getElementById('percentSize'),
                    resizeTabs = document.querySelectorAll('#resize-tab .nav-link'),
                    flipHBtn = document.getElementById('flipHorizontally'),
                    flipVBtn = document.getElementById('flipVertically'),
                    rotateCwBtn = document.getElementById('rotateCw'),
                    rotateCcwBtn = document.getElementById('rotateCcw'),
                    app = document.querySelector('body'),
                    downloadBtn = document.querySelector('.download-file-btn');
                let maxHeight = null,
                    maxWidth = null,
                    isSelected = false,
                    transform = {
                        horizontal: false,
                        vertical: false,
                        rotate: 0
                    };

                const attachEvents = function() {
                        ArtisanApp.initUpload(document.querySelector('.uploader-file-uploader'), {
                            dropOnBody: true,
                            maxFiles: 1,
                            fileExtensions: ".png|.jpg|.jpeg",
                            maxSize: {{ $tool->fs_tool }},
                            fileSelectedCallback: 'onFileSelect',
                        }, {
                            extensionsError: "{{ __('admin.fileTypeNotSupported') }}",
                            sizeError: "{{ __('admin.maxFileSizeError', ['size' => $tool->fs_tool]) }}",
                            filesError: "{{ __('admin.maxFileLimitError') }}",
                        });

                        if (app.classList.contains('tool-initialized')) {
                            return;
                        }
                        if (downloadBtn) {
                            downloadBtn.addEventListener('click', e => {
                                ArtisanApp.downloadFromUrl(e.target.dataset.url, e.target.dataset.filename)
                            })

                            return;
                        }
                        percentageField.addEventListener('change', e => {
                            const percent = e.target.value
                            if (percent < 20) {
                                percent = 20
                                e.target.value = percent
                            }

                            percentSize.innerHTML = `${percent}%`
                            maxDimensions.innerHTML =
                                `(${((percent / 100) * maxWidth).round(0)}x${((percent / 100) * maxHeight).round(0)})`
                        })
                        widthField.addEventListener('change', e => {
                            if (e.target.value > maxWidth) {
                                e.target.value = maxWidth
                            }
                        })
                        heightField.addEventListener('change', e => {
                            resizeField.value = 2
                            if (e.target.value > maxHeight) {
                                e.target.value = maxHeight
                            }
                        })
                        resizeTabs.forEach(tab => {
                            tab.addEventListener('show.bs.tab', e => {
                                resizeField.value = e.target.dataset.value
                            })
                        });
                        flipHBtn.addEventListener('click', e => {
                            transform.horizontal = !transform.horizontal
                            flipHField.value = transform.horizontal
                            updateStyle()
                        })
                        flipVBtn.addEventListener('click', e => {
                            transform.vertical = !transform.vertical
                            flipVField.value = transform.vertical
                            updateStyle()
                        })
                        rotateCwBtn.addEventListener('click', e => {
                            if (transform.rotate >= 360) {
                                transform.rotate = 0
                            }
                            transform.rotate = transform.rotate + 45
                            rotateField.value = transform.rotate
                            updateStyle()
                        })
                        rotateCcwBtn.addEventListener('click', e => {
                            if (transform.rotate == -360) {
                                transform.rotate = 0
                            }
                            transform.rotate = transform.rotate - 45
                            rotateField.value = transform.rotate
                            updateStyle()
                        })
                    },
                    updateStyle = function() {
                        const image = document.getElementById('image-editable')
                        let style = `rotate(${transform.rotate}deg)`;
                        if (transform.horizontal) {
                            style += 'scaleX(-1)'
                        }
                        if (transform.vertical) {
                            style += 'scaleY(-1)'
                        }
                        image.style.transform = style;
                    };

                return {
                    init: function() {
                        attachEvents();
                        app.classList.add('tool-initialized')
                    },
                    onFileSelect: function(event) {
                        if (isSelected) {
                            return;
                        }
                        const file = event[0];
                        const src = URL.createObjectURL(file)
                        const percent = percentageField.value
                        var image = new Image();
                        image.src = src;
                        image.id = 'image-editable';
                        image.className = 'img-fluid';
                        image.onload = function() {
                            console.log(this)
                            maxHeight = this.naturalHeight
                            maxWidth = this.naturalWidth
                            image.dataset.width = maxWidth
                            image.dataset.height = maxHeight
                            heightField.value = maxHeight
                            widthField.value = maxWidth
                            maxDimensions.innerHTML =
                                `(${((percent / 100) * maxWidth).round(0)}x${((percent / 100) * maxHeight).round(0)})`
                            percentSize.innerHTML = `${percent}%`
                        };

                        document.getElementById('imgEditable').appendChild(image)
                        document.getElementById('imageUploader').classList.add('d-none')
                        document.getElementById('imageEditor').classList.remove('d-none')
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
</x-tool-home-layout>
