<div class="container-fluid bg-light dark-mode-light-bg">
    <div class="container">
        @if (!isset($results))
            <div class="col-md-12">
                <div class="text-to-image-upload-wrap">
                    <div class="image-converter">
                        <x-form :route="route('front.index.action')" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <x-upload-wrapper max-files="1" :max-size="$tool->fs_tool" accept=".png,.jpg,jpeg"
                                        input-name="image" :file-title="__('tools.dropImageHereTitle')" :file-label="__('tools.dropImageHereLabel')">
                                        <div class="d-none text-center process-button">
                                            <x-button type="submit" class="btn btn-outline-primary">
                                                @lang('common.convertNow')
                                            </x-button>
                                        </div>
                                    </x-upload-wrapper>
                                </div>
                            </div>
                        </x-form>
                    </div>
                </div>
            </div>
        @endif
        @if (isset($results))
            <div class="tool-results-wrapper pt-4 pb-4">
                <x-page-wrapper :title="__('common.result')" :sub-title="$tool->description" class="mb-0">
                    <div class="tool-results result">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-shadow tabbar mb-3">
                                    <div class="large-text-scroller printable-result">
                                        {!! nl2br($results['text']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-ad-slot :advertisement="get_advert_model('below-result')" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div class="result-action-left">
                                        <x-copy-text :text="$results['text']" />
                                        <x-download-button :link="$results['download_url']" />
                                        <x-print-button id="printResult" />
                                    </div>
                                    <div class="result-action-right">
                                        <x-reload-button class="ms-auto" :link="route('tool.show', ['tool' => $tool->slug])" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-page-wrapper>
            </div>
        @endif
    </div>
</div>
