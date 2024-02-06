<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <x-input-label>@lang('tools.enterWebsiteUrl')</x-input-label>
                        <x-text-input class="form-control" name="url" id="url" type="url" required
                            value="{{ $url ?? old('url') }}" :placeholder="__('tools.enterOrPasteUrl')" />
                        <x-input-error :messages="$errors->get('url')" />
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <p class="text-center">@lang('tools.selectScreenshotDevice')</p>
                    <div class="radio-tile-group">
                        <div class="input-container">
                            <input id="dasktop" class="radio-button" type="radio" name="type" value="desktop"
                                @if (isset($type) && $type == 'desktop') checked @endif />
                            <div class="radio-tile">
                                <div class="icon dasktop-icon">
                                    <i class="an an-lcd an-2x"></i>
                                </div>
                                <label for="dasktop" class="radio-tile-label"></label>
                            </div>
                        </div>
                        <div class="input-container">
                            <input id="mobile" class="radio-button" type="radio" name="type" value="mobile"
                                @if (isset($type) && $type == 'mobile') checked @endif />
                            <div class="radio-tile">
                                <div class="icon mobile-icon">
                                    <i class="an an-mobile an-2x"></i>
                                </div>
                                <label for="mobile" class="radio-tile-label"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('above-form')" />
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12 text-end">
                        <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                            @lang('tools.generateScreenshot')
                        </x-button>
                    </div>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-center website-screenshot-detail">
                            <div class="{{ $type }}">
                                <div class="image">
                                    <div class="image-wrap">
                                        <img src="{{ $results['image'] }}" class="img-fluid screenshot"
                                            alt="{{ $tool->name }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3 text-center">
                        <x-button class="btn-outline-primary rounded-pill download-screenshot-btn" type="button"
                            onclick="ArtisanApp.downloadFromUrl('{{ $results['image'] }}', '{{ $results['filename'] }}')"
                            data-url="{{ $results['image'] }}" data-filename="{{ $results['filename'] }}">
                            {{ __('tools.downloadScreenshot') }}
                        </x-button>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
