<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-tool-property-display class="mb-3" :tool="$tool" name="fs_tool" label="maxFileSizeLimit" :plans="true"
            upTo="upTo100KB" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row mt-3 mb-3">
                <div class="col-md-12">
                    <x-upload-wrapper max-files="1" :max-size="$tool->fs_tool" accept=".jpg,.jpeg,.png"
                        input-name="file" :file-title="__('tools.dropImageHereTitle')">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-icon">
                                    <span class="icon">
                                        <i class="an an-search text-muted"></i>
                                    </span>
                                    <x-text-input type="text" name="keyword" class="form-control" :placeholder="__('tools.searchByKeyword')"
                                        id="keyword" value="{{ $results['keyword'] ?? '' }}" />
                                </div>
                                <x-input-error :messages="$errors->get('text')" class="mt-2" />
                            </div>
                            <div class="col-md-6">
                                <div class="input-icon">
                                    <span class="icon">
                                        <i class="an an-link text-muted"></i>
                                    </span>
                                    <x-text-input type="url" name="url" class="form-control" :placeholder="__('tools.searchByUrl')"
                                        id="url" value="{{ $results['url'] ?? '' }}" />
                                </div>
                                <x-input-error :messages="$errors->get('url')" class="mt-2" />
                            </div>
                        </div>
                    </x-upload-wrapper>
                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row mb-4">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.searchSimilarImages')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')" :sub-title="__('tools.similarImagesSearchEngines')">
                <div class="result mt-4">
                    <div class="row">
                        @foreach ($results['searches'] as $search)
                            <div class="col-md-4">
                                <div class="wrap-content box-shadow">
                                    <div class="d-flex">
                                        <div class="btn-searches">
                                            <i class="an an-{{ $search['icon'] }} btn-{{ $search['icon'] }}"></i>
                                        </div>
                                        <p class="ms-3">@lang('tools.similarImagesAccording', ['name' => $search['name']])</p>
                                    </div>
                                    <a class="btn btn-primary rounded-pill" href="{{ $search['url'] }}" target="_blank"
                                        rel="noopener noreferrer">@lang('tools.showMatches')</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
