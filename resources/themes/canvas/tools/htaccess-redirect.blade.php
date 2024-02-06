<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <x-input-label for="domain">@lang('tools.domainName', ['count' => $tool->no_domain_tool])</x-input-label>
                            <x-text-input class="form-control" name="domain" id="domain" :placeholder="__('tools.enterADomain')" autofocus
                                required :error="$errors->has('domain')" required value="{{ $results['domain'] ?? old('domain') }}" />
                            <x-input-error :messages="$errors->get('domain')" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="fw-bold">
                            <x-input-label>@lang('tools.selectRedirectType')</x-input-label>
                        </div>
                        <div class="form-group">
                            <label class="radio-checkbox-wrapper">
                                <input type="radio" id="toggle" class="radio-checkbox-input" name="type"
                                    value="1" @if (isset($type) && $type == '1') checked @endif />
                                <span class="radio-checkbox-tile">
                                    <span>@lang('tools.fromwwwtonon')</span>
                                </span>
                            </label>
                            <label class="radio-checkbox-wrapper">
                                <input class="radio-checkbox-input" id="sentence" type="radio" name="type"
                                    value="2" autocomplete="off" @if (isset($type) && $type == '2') checked @endif>
                                <span class="radio-checkbox-tile">
                                    <span>@lang('tools.fromnontoww')</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.getYourHtAccessCode')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot />
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <p>@lang('tools.copyHtaccessCodeHelp')</p>
                        </div>
                        <div class="col-md-12">
                            <x-textarea-input rows="6" type="text" class="form-control h-80" id="save-as-file">
                                {{ $results['content'] ?? '' }}
                            </x-textarea-input>
                        </div>
                        <div class="col-md-12 text-end">
                            <x-button class="btn btn-primary rounded-pill mt-2" type="button"
                                onclick="ArtisanApp.downloadAsTxt('#save-as-file', {filename: '{{ $tool->slug . '.txt' }}'})">
                                @lang('tools.saveAsTxt')
                            </x-button>
                            <x-copy-target class="btn-primary mt-2" :svg="false" target="save-as-file"
                                :text="__('common.copyToClipboard')" />
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
