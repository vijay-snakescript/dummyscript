<x-application-tools-wrapper>
    @if (!isset($results))
        <x-tool-wrapper :tool="$tool">
            <x-ad-slot :advertisement="get_advert_model('above-form')" />
            <x-form class="mb-3" :route="route('tool.handle', $tool->slug)" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <div id="imageUploader" class="form-group">
                            <x-upload-wrapper max-files="1" :max-size="$tool->fs_tool" accept=".png,.jpg,jpeg"
                                input-name="image" :file-title="__('tools.dropImageHereTitle')" :file-label="__('tools.changeImageHeightAndWidth')" on-select-file="onFileSelect" />
                        </div>
                        <div id="imageEditor" class="d-none">
                            <div class="resize-image result mt-4">
                                <div class="controller tabbar border mb-3 d-flex flex-column">
                                    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" data-bs-toggle="tab"
                                                data-bs-target="#resize-tab" type="button" role="tab"
                                                aria-controls="resize-tab" aria-selected="true">
                                                <i class="an an-resize"></i>
                                                @lang('tools.resize')
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flip-tab"
                                                type="button" role="tab" aria-controls="flip-tab"
                                                aria-selected="false">
                                                <i class="an an-flip-vertically"></i>
                                                @lang('tools.flip')
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rotate-tab"
                                                type="button" role="tab" aria-controls="rotate-tab"
                                                aria-selected="false">
                                                <i class="an an-rotate"></i>
                                                @lang('tools.rotate')
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content mh-300">
                                        <div class="tab-pane active" id="resize-tab" role="tabpanel"
                                            aria-labelledby="resize-tab">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <button class="nav-link active ms-2" id="percentage-tab"
                                                    data-bs-toggle="tab" data-bs-target="#percentage" type="button"
                                                    role="tab" aria-controls="percentage" data-value="1"
                                                    aria-selected="true">@lang('tools.asAPercent')</button>

                                                <button class="nav-link" id="dimension-tab" data-bs-toggle="tab"
                                                    data-bs-target="#dimension" type="button" role="tab"
                                                    aria-controls="dimension" data-value="2"
                                                    aria-selected="false">@lang('tools.byDimensions')</button>
                                            </ul>
                                            <div class="tab-content p-2">
                                                <div class="tab-pane active" id="percentage" role="tabpanel"
                                                    aria-labelledby="percentage-tab">
                                                    <div class="range-slider mb-3">
                                                        <input id="percentageField"
                                                            class="range-slider__range change-listner" name="percentage"
                                                            type="range" value="75" min="20" max="100">
                                                        <span class="range-slider__value"></span>
                                                    </div>
                                                    <div class="text-center mt-4 mb-3">
                                                        @lang('tools.originalSizePercentageHelp')
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="dimension" role="tabpanel"
                                                    aria-labelledby="dimension-tab">
                                                    <div class="form-group mb-3">
                                                        <x-input-label for="num" class="form-label">
                                                            @lang('tools.height')
                                                        </x-input-label>
                                                        <input type="number" class="form-control" id="height"
                                                            name="height">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <x-input-label for="num2" class="form-label">
                                                            @lang('tools.width')
                                                        </x-input-label>
                                                        <input type="number" class="form-control" id="width"
                                                            name="width">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <x-input-label>@lang('tools.saveImageAs')</x-input-label>
                                                    <select class="form-select" name="format" id="format">
                                                        <option value="">@lang('tools.original')</option>
                                                        <option value="png">PNG</option>
                                                        <option value="jpg">JPG</option>
                                                        <option value="webp">WEBP</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane p-2" id="flip-tab" role="tabpanel"
                                            aria-labelledby="flip-tab">
                                            <x-button type="button" id="flipHorizontally"
                                                class="btn-outline-primary d-block w-100 mb-3">
                                                <i class="an an-flip-horizontally"></i>
                                                @lang('tools.flipHorizontally')
                                            </x-button>
                                            <x-button type="button" id="flipVertically"
                                                class="btn-outline-primary d-block w-100 mb-3">
                                                <i class="an an-flip-vertically"></i>
                                                @lang('tools.flipVertically')
                                            </x-button>
                                        </div>
                                        <div class="tab-pane p-2" id="rotate-tab" role="tabpanel"
                                            aria-labelledby="rotate-tab">
                                            <x-button type="button" id="rotateCw"
                                                class="btn-outline-primary d-block w-100 mb-3">
                                                <i class="an an-rotate"></i>
                                                @lang('tools.rotateImage')
                                            </x-button>
                                            <x-button type="button" id="rotateCcw"
                                                class="btn-outline-primary d-block w-100 mb-3">
                                                <i class="an an-rotate-left"></i>
                                                @lang('tools.rotateImage')
                                            </x-button>
                                        </div>
                                    </div>
                                    <div class="btn-action d-grid mt-auto p-2">
                                        <x-button type="submit" class="btn-primary">@lang('tools.resizeImage')</x-button>
                                    </div>
                                </div>
                                <div class="details">
                                    <input id="resize" type="hidden" name="resize" value="1">
                                    <input id="rotate" type="hidden" name="rotate" value="0">
                                    <input id="flip_horizontal" type="hidden" name="flip_horizontal"
                                        value="">
                                    <input id="flip_vertical" type="hidden" name="flip_vertical" value="">
                                    <div id="imgEditable" class="img-wrap text-center overflow-hidden"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-form>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
        </x-tool-wrapper>
    @else
        <x-page-wrapper :title="__('common.result')">
            <div class="result mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-shadow tabbar mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="resizer-file">
                                    <h3 class="h4">{{ $results['original_filename'] }}</h3>
                                    <span>{{ formatSizeUnits($results['size']) }}</span>
                                </div>
                                <div class="resizer-action">
                                    <button class="btn btn-outline-primary rounded-pill download-file-btn"
                                        type="button"data-url="{{ url($results['url']) }}"
                                        data-filename="{{ $results['original_filename'] }}">
                                        {{ __('common.download') }}
                                    </button>
                                    <x-reload-button :tooltip="__('tools.resizeMoreImages')" :link="route('tool.show', ['tool' => $tool->slug])" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-page-wrapper>
    @endif
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
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
</x-application-tools-wrapper>
