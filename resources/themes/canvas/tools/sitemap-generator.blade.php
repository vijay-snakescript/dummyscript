<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <x-input-label class="h4 mb-3">@lang('tools.enterWebsiteUrl')</x-input-label>
                        <x-text-input class="form-control" name="url" id="url" type="url" required
                            value="{{ $results['url'] ?? old('url') }}" :placeholder="__('tools.enterOrPasteUrl')" />
                        <x-input-error :messages="$errors->get('url')" />
                        <span class="text-muted small">@lang('tools.generateSitemapHelp')</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn btn-outline-primary rounded-pill">
                        @lang('tools.generateSitemap')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results) && isset($results['content']))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="tool-results">
                    <div class="custom-textarea p-3">
                        <x-textarea-input id="xmlSitemap" readonly class="transparent" readonly rows="8">
                            {{ $results['content'] }}
                        </x-textarea-input>
                    </div>
                </div>
                <div class="result-copy mt-3 text-end">
                    <x-copy-target target="xmlSitemap" :text="__('common.copyToClipboard')" :svg="false" />
                    <x-form class="download-btn d-inline-block no-app-loader" metho="post" :route="route('tool.postAction', ['tool' => $tool->slug, 'action' => 'download'])">
                        <input type="hidden" name="process_id" value="{{ $results['process_id'] }}">
                        <x-button class="btn btn-primary rounded-pill" type="submit">
                            @lang('tools.downloadSitemap')
                        </x-button>
                    </x-form>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
