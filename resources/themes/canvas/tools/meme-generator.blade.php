<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-tool-property-display class="mb-3" :tool="$tool" name="fs_tool" label="maxFileSizeLimit" :plans="true"
            upTo="upTo100KB" />
        <div class="row">
            <div class="col-md-12 meme-editer">
                <div id="canvasPlaceholder" class="form-group">
                    <x-upload-wrapper max-files="1" :max-size="$tool->fs_tool" accept=".png,.jpg,.jpeg,.gif"
                        input-name="image" :file-title="__('tools.dropImageHereTitle')" :file-label="__('tools.animatedFilesSupported')" on-select-file="handleFileSelect" />
                </div>
                <div class="canvas-area d-none" id="canvas-cont">
                    <div class="canvas-image position-relative">
                        <div id="show_gif" class="canvas">
                            <img id="gifImage" />
                        </div>
                        <canvas id="canvas" class="canvas position-absolute top-0 left-0"></canvas>
                    </div>
                    <div class="controller ms-3 d-flex flex-column">
                        <div id="inputsContainer"></div>
                        <div class="new-text-layer text-end mb-3">
                            <x-button id="addTextboxBtn" type="button" class="btn btn-light">
                                @lang('tools.addNewTextLayer')
                            </x-button>
                        </div>
                        <div class="d-grid mt-auto">
                            <x-button id="generateMemeBtn" type="button" class="btn btn-primary">
                                @lang('tools.generateMeme')
                            </x-button>
                        </div>
                    </div>
                </div>
                <div class="d-none justify-content-center mt-3 loader-search" id="loading">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">@lang('common.loading')</span>
                    </div>
                </div>
            </div>
        </div>
    </x-tool-wrapper>
    <div class="meme-results-wrapper">
        <x-page-wrapper id="memeResult" class="d-none" :title="__('common.result')" :sub-title="__('tools.memeGenerated')">
            <div class="result mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-none justify-content-center mt-3 loader-search" id="result-loading">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">@lang('common.loading')</span>
                            </div>
                        </div>
                        <img class="img-fluid mb-5" src="" alt="download meme" id="downloadMemePreview" />
                        <div class="download-action text-end">
                            <a class="btn rounded-pill btn-outline-primary" href="#" download=""
                                id="downloadMemeBtn" data-bs-toggle="tooltip" title="{{ __('common.download') }}">
                                <i class="an an-download"></i>
                                @lang('tools.downloadMeme')
                            </a>
                            <x-reload-button :tooltip="__('tools.createNewMeme')" :link="route('tool.show', ['tool' => $tool->slug])" />
                        </div>
                    </div>
                </div>
            </div>
        </x-page-wrapper>
    </div>
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        @vite(['resources/themes/canvas/assets/js/meme/meme.js'])
    @endpush
</x-application-tools-wrapper>
