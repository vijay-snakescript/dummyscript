<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-tool-property-display class="mb-3" :tool="$tool" name="fs_tool" label="maxFileSizeLimit" :plans="true"
            upTo="upTo100KB" />
        <x-form :route="route('tool.handle', $tool->slug)" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <x-upload-wrapper max-files="1" :max-size="$tool->fs_tool" accept=".jpg,.jpeg" input-name="image" :file-title="__('tools.dropImageHereTitle')"
                        :file-label="__('tools.dropImageHereLabel')" />
                </div>
            </div>
            <x-ad-slot class="mb-3" :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12">
                    <div class="text-end">
                        <button type="submit" class="btn btn-outline-primary rounded-pill">@lang('common.convertNow')</button>
                    </div>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('bellow-form')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="tool-results result mt-4">
                    <x-ad-slot :advertisement="get_advert_model('above-result')" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex p-2 justify-content-between align-items-center border">
                                <div class="results-title">
                                    <div class="results-icon"></div>
                                    <strong>
                                        {{ $results['filename'] . '.doc' }}
                                    </strong>
                                </div>
                                <div class="results-action">
                                    <x-download-button :text="__('common.download')" :link="$results['download_url']" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center mt-3">
                            <x-reload-button class="ms-auto" :link="route('tool.show', ['tool' => $tool->slug])" />
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
