<div class="container-fluid bg-light dark-mode-light-bg py-4">
    <div class="container">
        <x-form method="post" :route="route('front.index.action')">
            <x-tool-property-display :tool="$tool" name="wc_tool" label="wordCountLimit" :plans="true"
                upTo="upTo30k">
            </x-tool-property-display>
            <div class="row mb-4">
                <div class="col-md-12 mt-2 mb-3">
                    <div class="form-group">
                        <x-textarea-input type="text" name="string" class="form-control" rows="8"
                            :placeholder="__('common.someText')" id="textarea" required autofocus contenteditable="true">
                            {{ $results['original_article'] ?? old('string') }}
                        </x-textarea-input>
                    </div>
                    <x-input-error :messages="$errors->get('string')" class="mt-2" />
                </div>
                <div class="col-md-6">
                    <x-input-file-button />
                </div>
                <div class="col-md-6 text-end">
                    <x-button type="submit" class="btn-outline-primary rounded-pill">
                        @lang('tools.rewriteArticle')
                    </x-button>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
        </x-form>
        @if (isset($results))
            <div class="tool-results-wrapper">
                <x-ad-slot :advertisement="get_advert_model('above-result')" />
                <x-page-wrapper :title="__('common.result')" class="mb-0">
                    <div class="result mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-shadow tabbar mb-3 custom-textarea">
                                    <x-textarea-input id="rewrite-result" class="form-control transparent"
                                        rows="12">
                                        {{ $results['article_rewrite'] }}
                                    </x-textarea-input>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-copy-target target="rewrite-result" />
                                        <x-download-form-button type="button"
                                            onclick="ArtisanApp.downloadAsTxt('#rewrite-result', {filename: '{{ $tool->slug . '.txt' }}'})"
                                            :tooltip="__('tools.saveAsTxt')" />
                                        <x-print-button
                                            onclick="ArtisanApp.printResult(document.querySelector('#rewrite-result'), {title: '{{ $tool->name }}'})"
                                            :tooltip="__('tools.printResult')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-page-wrapper>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-result')" />
        @endif
    </div>
</div>
