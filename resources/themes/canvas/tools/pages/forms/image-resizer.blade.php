<div class="container-fluid bg-light py-4 dark-mode-light-bg">
    <div class="container">
        <div class="text-to-image-upload-wrap">
            <div class="image-converter">
                <x-form :route="route('front.index.action')" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="imageUploader" class="form-group">
                                <x-upload-wrapper max-files="1" :max-size="$tool->fs_tool" accept=".png,.jpg,jpeg"
                                    input-name="image" :file-title="__('tools.dropImageHereTitle')" :file-label="__('tools.changeImageHeightAndWidth')"
                                    on-select-file="onFileSelect" />
                            </div>
                            <div id="imageEditor" class="d-none bg-white">
                                <div class="resize-image result">
                                    <div class="controller tabbar border d-flex flex-column">
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
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#rotate-tab" type="button" role="tab"
                                                    aria-controls="rotate-tab" aria-selected="false">
                                                    <i class="an an-rotate"></i>
                                                    @lang('tools.rotate')
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
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
                                                                class="range-slider__range change-listner"
                                                                name="percentage" type="range" value="75"
                                                                min="20" max="100">
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
                                        <input id="flip_vertical" type="hidden" name="flip_vertical"
                                            value="">
                                        <div id="imgEditable" class="ps-0 img-wrap text-center overflow-hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-form>
            </div>
        </div>
    </div>

    @if (isset($results))
        <div class="container-fluid bg-light py-5 dark-mode-light-bg">
            <div class="result container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="hero-title center bold">
                            <h1>{{ __('common.result') }}</h1>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-style">
                            <tbody>
                                <tr>
                                    <td><span class="ps-2 fw-bold">{{ $results['original_filename'] }}</span></td>
                                    <td>{{ formatSizeUnits($results['size']) }}</td>
                                    <td>
                                        <button class="btn btn-outline-primary rounded-circle download-file-btn"
                                            type="button" id="button" data-toggle="tooltip"
                                            aria-label="Download" data-url="{{ url($results['url']) }}"
                                            data-filename="{{ $results['original_filename'] }}">
                                            <i class="an an-long-arrow-down"></i>
                                        </button>
                                        <x-reload-button :tooltip="__('tools.resizeMoreImages')" :link="route('front.index')" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
