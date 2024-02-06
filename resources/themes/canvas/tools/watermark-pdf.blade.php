<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-8">
                    <div id="imageUploader" class="form-group h-100">
                        <x-pdf-upload-wrapper :max-files="$tool->no_file_tool" :max-size="$tool->fs_tool" accept=".pdf" input-name="files[]"
                            pages="false" rotate="false" :equal-height="true" allow-protected-files="false" preview="true"
                            sortable="true" />
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="nav nav-tabs nav-fill" id="watermark-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" id="watermark-text-tab"
                                class="nav-link @if (old('watermark', 'text') == 'text') active @endif" data-bs-toggle="tab"
                                data-bs-target="#watermark-text" role="tab" aria-controls="text"
                                aria-selected="true" data-tab="text">
                                <i class="an an-text"></i>
                                @lang('tools.placeText')
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" id="watermark-image-tab"
                                class="nav-link @if (old('watermark', 'text') == 'image') active @endif" data-bs-toggle="tab"
                                data-bs-target="#watermark-image" role="tab" aria-controls="image"
                                aria-selected="false" data-tab="image">
                                <i class="an an-image"></i>
                                @lang('tools.placeImage')
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="watermark-contents">
                        <div class="tab-pane fade @if (old('watermark', 'text') == 'text') show active @endif"
                            id="watermark-text" role="tabpanel" aria-labelledby="watermark-text">
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <x-input-label for="input-text">
                                            @lang('tools.text')
                                        </x-input-label>
                                        <x-text-input id="input-text" name="text" class="form-control-sm"
                                            value="{{ $results['text'] ?? old('text') }}" :placeholder="__('common.watermarkText')" />
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="btn-toolbar">
                                            <div class="btn-group me-2">
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                                    data-bs-toggle="dropdown" title="Font" aria-expanded="false">
                                                    <i class="an an-text"></i>
                                                </button>
                                                <ul class="dropdown-menu fontsList">
                                                </ul>
                                            </div>
                                            <div class="btn-group me-2">
                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                                    data-bs-toggle="dropdown" title="Font Size">
                                                    <span class="fw-bold">A</span>
                                                </button>
                                                <ul class="dropdown-menu fontSize" style="min-width: 250px">
                                                    <li>
                                                        <div class="px-3">
                                                            <label class="form-label"
                                                                for="font-size">@lang('common.fontSize')</label>
                                                            <div class="form-group d-flex">
                                                                <div class="range-slider">
                                                                    <input id="font-size" name="text-size"
                                                                        class="range-slider__range change-listner"
                                                                        type="range" value="0" min="10"
                                                                        max="60">
                                                                    <span class="range-slider__value"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="btn-group">
                                                <div class="dropdown">
                                                    <button class="btn btn-light dropdown-toggle" type="button"
                                                        id="colorDropdown" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <span class="selected-color position-relative"
                                                            id="currentColor"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" style="width:210px"
                                                        aria-labelledby="colorDropdown">
                                                        <li>
                                                            <div class="px-2">
                                                                <div class="color-tiles-container d-flex flex-wrap"
                                                                    id="colorTilesContainer"></div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <x-input-label for="select-position">
                                            @lang('common.position')
                                        </x-input-label>
                                        <select id="select-position" name="text-position"
                                            class="form-select form-select-sm">
                                            <option value="top-left" @if (old('text-position') == 'top-left') selected @endif>
                                                @lang('common.topLeft')
                                            </option>
                                            <option value="top-center"
                                                @if (old('text-position') == 'top-left') selected @endif>
                                                @lang('common.topCenter')
                                            </option>
                                            <option value="top-right"
                                                @if (old('text-position') == 'top-right') selected @endif>
                                                @lang('common.topRight')
                                            </option>
                                            <option value="center" @if (old('text-position') == 'center') selected @endif>
                                                @lang('common.center')
                                            </option>
                                            <option value="bottom-left"
                                                @if (old('text-position') == 'bottom-left') selected @endif>
                                                @lang('common.bottomLeft')
                                            </option>
                                            <option value="bottom-center"
                                                @if (old('text-position') == 'bottom-center') selected @endif>
                                                @lang('common.bottomCenter')
                                            </option>
                                            <option value="bottom-right"
                                                @if (old('text-position') == 'bottom-right') selected @endif>
                                                @lang('common.bottomRight')
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <x-input-label for="select-position">
                                            @lang('common.transparency')
                                        </x-input-label>
                                        <select id="select-transparency" name="text-transparency"
                                            class="form-select form-select-sm">
                                            <option value="100" @if (old('text-transparency') == '100') selected @endif>
                                                @lang('common.noTransparency')</option>
                                            <option value="25" @if (old('text-transparency') == '25') selected @endif>
                                                25%</option>
                                            <option value="50" @if (old('text-transparency') == '50') selected @endif>
                                                50%</option>
                                            <option value="75" @if (old('text-transparency') == '75') selected @endif>
                                                75%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <x-input-label for="select-rotation">
                                            @lang('common.rotation')
                                        </x-input-label>
                                        <select id="select-rotation" name="text-rotation"
                                            class="form-select form-select-sm">
                                            <option value="0" @if (old('text-rotation') == '0') selected @endif>
                                                @lang('common.doNotRotate')</option>
                                            <option value="45" @if (old('text-rotation') == '45') selected @endif>
                                                @lang('common.rotateDegree', ['number' => 45])</option>
                                            <option value="90" @if (old('text-rotation') == '90') selected @endif>
                                                @lang('common.rotateDegree', ['number' => 90])</option>
                                            <option value="180" @if (old('text-rotation') == '180') selected @endif>
                                                @lang('common.rotateDegree', ['number' => 180])</option>
                                            <option value="270" @if (old('text-rotation') == '270') selected @endif>
                                                @lang('common.rotateDegree', ['number' => 270])</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <x-input-label for="select-layer">
                                        @lang('common.layer')
                                    </x-input-label>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input type="radio" class="radio-checkbox-input" name="text-overlay"
                                                value="1" @if (old('text-overlay', 1) == '1') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('common.watermarkOverlay')</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input type="radio" class="radio-checkbox-input" name="text-overlay"
                                                value="2" @if (old('text-overlay', 1) == '2') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('common.watermarkUnderlay')</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade @if (old('watermark', 'text') == 'image') show active @endif"
                            id="watermark-image" role="tabpanel" aria-labelledby="watermark-image">
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <div class="form-group mb-3 d-flex justify-content-center">
                                        <div class="upload-wrapper">
                                            <div class="file-upload" id="image-preview">
                                                <input name="image" id="image-field" type="file"
                                                    accept=".png,.jpg,jpeg" />
                                                <i class="an an-upload-image"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <x-input-label for="select-position">
                                            @lang('common.position')
                                        </x-input-label>
                                        <select id="select-position" name="image-position"
                                            class="form-select form-select-sm">
                                            <option value="top-left"
                                                @if (old('image-position') == 'top-left') selected @endif>
                                                @lang('common.topLeft')
                                            </option>
                                            <option value="top-center"
                                                @if (old('image-position') == 'top-left') selected @endif>
                                                @lang('common.topCenter')
                                            </option>
                                            <option value="top-right"
                                                @if (old('image-position') == 'top-right') selected @endif>
                                                @lang('common.topRight')
                                            </option>
                                            <option value="center" @if (old('image-position') == 'center') selected @endif>
                                                @lang('common.center')
                                            </option>
                                            <option value="bottom-left"
                                                @if (old('image-position') == 'bottom-left') selected @endif>
                                                @lang('common.bottomLeft')
                                            </option>
                                            <option value="bottom-center"
                                                @if (old('image-position') == 'bottom-center') selected @endif>
                                                @lang('common.bottomCenter')
                                            </option>
                                            <option value="bottom-right"
                                                @if (old('image-position') == 'bottom-right') selected @endif>
                                                @lang('common.bottomRight')
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <x-input-label for="select-transparency">
                                            @lang('common.transparency')
                                        </x-input-label>
                                        <select id="select-transparency" name="image-transparency"
                                            class="form-select form-select-sm">
                                            <option value="100" @if (old('image-transparency') == '100') selected @endif>
                                                @lang('common.noTransparency')</option>
                                            <option value="25" @if (old('image-transparency') == '25') selected @endif>
                                                25%</option>
                                            <option value="50" @if (old('image-transparency') == '50') selected @endif>
                                                50%</option>
                                            <option value="75" @if (old('image-transparency') == '75') selected @endif>
                                                75%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <x-input-label for="select-rotation">
                                            @lang('common.rotation')
                                        </x-input-label>
                                        <select id="select-rotation" name="image-rotation"
                                            class="form-select form-select-sm">
                                            <option value="0" @if (old('image-rotation') == '0') selected @endif>
                                                @lang('common.doNotRotate')</option>
                                            <option value="45" @if (old('image-rotation') == '45') selected @endif>
                                                @lang('common.rotateDegree', ['number' => 45])</option>
                                            <option value="90" @if (old('image-rotation') == '90') selected @endif>
                                                @lang('common.rotateDegree', ['number' => 90])</option>
                                            <option value="180" @if (old('image-rotation') == '180') selected @endif>
                                                @lang('common.rotateDegree', ['number' => 180])</option>
                                            <option value="270" @if (old('image-rotation') == '270') selected @endif>
                                                @lang('common.rotateDegree', ['number' => 270])</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <x-input-label for="select-layer">
                                        @lang('common.layer')
                                    </x-input-label>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input type="radio" class="radio-checkbox-input" name="image-overlay"
                                                value="1" @if (old('image-overlay', 1) == '1') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('common.watermarkOverlay')</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input type="radio" class="radio-checkbox-input" name="image-overlay"
                                                value="2" @if (old('image-overlay', 1) == '2') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('common.watermarkUnderlay')</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <input type="hidden" name="watermark" id="watermark" value="{{ old('watermark', 'text') }}">
                    <input type="hidden" id="selectedColor" name="watermark-color"
                        value="{{ old('watermark-color', '#000000') }}">
                    <input type="hidden" name="font-family" id="font-family"
                        value="{{ old('font-family', 'impact') }}">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.addWatermark')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4">
                    <table class="table table-style">
                        <thead>
                            <tr>
                                <th>@lang('common.fileName')</th>
                                <th width="200">@lang('common.size')</th>
                                <th width="150"></th>
                            </tr>
                        </thead>
                        <tbody id="processing-files">
                            @foreach ($results['files'] as $file)
                                <tr>
                                    <td>
                                        <div class="mw-350 text-truncate fw-bold">{{ $file['filename'] }}</div>
                                    </td>
                                    <td>{{ formatSizeUnits($file['size']) }}</td>
                                    <td id="file-cursor">
                                        <button class="btn btn-outline-primary rounded-circle download-file-btn"
                                            data-bs-toggle="tooltip" title="@lang('common.download')" type="button"
                                            id="download-file" data-url="{{ $file['url'] }}"
                                            data-filename="{{ $file['filename'] }}">
                                            <i class="an an-long-arrow-down"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12 text-end">
                        @if (count($results['files']) > 1)
                            <x-form class="no-app-loader download-all-btn d-inline-block" method="post"
                                :route="route('tool.postAction', [
                                    'tool' => $tool->slug,
                                    'action' => 'download-all',
                                ])">
                                <input type="hidden" name="process_id" value="{{ $results['process_id'] }}">
                                <x-download-form-button :text="__('tools.downloadAll')" />
                            </x-form>
                        @endif
                        <x-reload-button :link="route('tool.show', ['tool' => $tool->slug])" />
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <style>
            .color-tile,
            .selected-color {
                width: 1rem;
                height: 1rem;
                display: inline-block;
                cursor: pointer;
            }

            .selected-color {
                top: 2px;
            }
        </style>
        <script src="{{ url('themes/default/js/pdf/pdf.min.js') }}"></script>
        @vite(['resources/themes/canvas/assets/js/app-pdf.js'])
        <script>
            pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ url('themes/default/js/pdf/pdf.worker.min.js') }}';
            const APP = function() {
                let isSelected = false;
                const watermarkField = document.getElementById('watermark'),
                    watermarkTabs = document.querySelectorAll('#watermark-tabs .nav-link'),
                    fonts = ['Arial', 'Arial Black', 'Courier', 'Courier Bold', 'Courier New', 'Comic Sans MS',
                        'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times New Roman',
                        'Verdana'
                    ],
                    fontFamilyField = document.querySelector('#font-family'),
                    colorTiles = ["#000000", "#444444", "#666666", "#999999", "#cccccc", "#eeeeee", "#f3f3f3", "#ffffff",
                        "#ff0000", "#ff9900", "#ffff00", "#00ff00", "#00ffff", "#0000ff", "#9900ff", "#ff00ff", "#f4cccc",
                        "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc", "#ea9999", "#f9cb9c",
                        "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd", "#e06666", "#f6b26b", "#ffd966",
                        "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0", "#cc0000", "#e69138", "#f1c232", "#6aa84f",
                        "#45818e", "#3d85c6", "#674ea7", "#a64d79", "#990000", "#b45f06", "#bf9000", "#38761d", "#134f5c",
                        "#0b5394", "#351c75", "#741b47", "#660000", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763",
                        "#20124d", "#4c1130"
                    ],
                    colorTilesContainer = document.getElementById('colorTilesContainer'),
                    watermarkImageField = document.querySelector('#image-field'),
                    watermarkImagePreview = document.querySelector('#image-preview'),
                    sizeSelected = document.querySelector('.sizeSelected'),
                    fontTarget = document.querySelector('.fontsList.dropdown-menu');

                var defaultFont = '{{ old('font-family', 'Impact') }}',
                    defaultColor = '{{ old('watermark-color', '#000000') }}';

                const attachEvents = function() {
                    watermarkImageField.onchange = evt => {
                        const [file] = watermarkImageField.files
                        if (file) {
                            const blob = URL.createObjectURL(file);
                            watermarkImagePreview.style.background = `url(${blob}) no-repeat center / cover`
                        }
                    }

                    document.getElementById('selectedColor').value = defaultColor;
                    document.getElementById('currentColor').style.backgroundColor = defaultColor;
                    fonts.forEach(font => {
                        const fontItem = document.createElement('div');
                        fontItem.classList.add('dropdown-item', 'font-item');
                        if (defaultFont == font) {
                            fontItem.classList.add('active');
                        }
                        fontItem.textContent = font;
                        fontItem.style.fontFamily = font;

                        fontItem.addEventListener('click', function() {
                            defaultFont = this.textContent;
                            fontFamilyField.value = defaultFont;
                            document.querySelectorAll('.font-item').forEach(element => {
                                if (element.classList.contains('active')) {
                                    element.classList.remove('active')
                                }
                                if (element.textContent == defaultFont) {
                                    element.classList.add('active')
                                }
                            });
                        }, true);

                        fontTarget.appendChild(fontItem);
                    });
                    watermarkTabs.forEach(tab => {
                        tab.addEventListener('show.bs.tab', e => {
                            watermarkField.value = e.target.dataset.tab
                        })
                    });
                    colorTiles.forEach(color => {
                        const tile = document.createElement('div');
                        tile.classList.add('color-tile', 'm-1');
                        tile.style.backgroundColor = color;
                        tile.addEventListener('click', function() {
                            const selectedColor = this.style.backgroundColor;
                            document.getElementById('selectedColor').value = selectedColor;
                            document.getElementById('currentColor').style.backgroundColor =
                                selectedColor;
                        });

                        colorTilesContainer.appendChild(tile);
                    });
                    @if (isset($results))
                        document.querySelectorAll('.download-file-btn').forEach(button => {
                            button.addEventListener('click', e => {
                                const element = e.target.classList.contains('.download-file-btn') ?
                                    e
                                    .target : e.target.closest('.download-file-btn')
                                ArtisanApp.downloadFromUrl(element.dataset.url, element.dataset
                                    .filename)
                            })
                        });
                    @endif
                };
                return {
                    init: function() {
                        attachEvents();
                    },
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
