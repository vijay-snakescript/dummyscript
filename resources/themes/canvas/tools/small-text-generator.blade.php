<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-tool-property-display :tool="$tool" name="wc_tool" label="wordCountLimit" :plans="true"
            upTo="upTo30k" />
        <div class="panel-left-generator box-shadow mt-3 mb-3 py-5">
            <div class="row">
                <div class="col-md-12">
                    <x-form method="post" :route="route('tool.handle', $tool->slug)">
                        <div class="panel-left-radio">
                            <div class="panel-left">
                                <div class="controller mb-3">
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input type="radio" id="toggle" class="radio-checkbox-input"
                                                name="type" value="1"
                                                @if (isset($type) && $type == '1') checked @endif />
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.smallText')</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input class="radio-checkbox-input" id="sentence" type="radio"
                                                name="type" value="2" autocomplete="off"
                                                @if (isset($type) && $type == '2') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.upsideDown')</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input class="radio-checkbox-input" id="lower" type="radio"
                                                name="type" value="3" autocomplete="off"
                                                @if (isset($type) && $type == '3') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.smallCaps')</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input class="radio-checkbox-input" id="upper" type="radio"
                                                name="type" value="4" autocomplete="off"
                                                @if (isset($type) && $type == '4') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.boldText')</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input class="radio-checkbox-input" id="capitalize" type="radio"
                                                name="type" value="5" autocomplete="off"
                                                @if (isset($type) && $type == '5') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.bubbleText')</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input class="radio-checkbox-input" id="capitalize" type="radio"
                                                name="type" value="6" autocomplete="off"
                                                @if (isset($type) && $type == '6') checked @endif>
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.backwardsText')</span>
                                            </span>
                                        </label>
                                    </div>
                                    <x-button type="submit" class="btn btn-primary rounded-pill">
                                        @lang('common.generate')
                                    </x-button>
                                    @if (isset($results))
                                        <x-button class="btn btn-primary rounded-pill mt-2" type="button"
                                            onclick="ArtisanApp.downloadAsTxt('#save-as-file', {filename: '{{ $tool->slug . '.txt' }}'})">
                                            @lang('tools.saveAsTxt')
                                        </x-button>
                                        <x-copy-target class="btn-primary mt-2" :svg="false" target="save-as-file"
                                            :text="__('common.copyToClipboard')" />
                                    @endif
                                </div>
                                <div class="textarea d-flex flex-column justify-content-between">
                                    <x-textarea-input type="text" name="string" class="form-control h-50 mb-3"
                                        :placeholder="__('common.someText')" id="textarea" required autofocus>
                                        {{ $results['original_text'] ?? '' }}
                                    </x-textarea-input>
                                    <x-input-error :messages="$errors->get('string')" class="mt-2" />
                                    <x-textarea-input type="text" class="form-control h-50" id="save-as-file">
                                        {{ $results['converted_text'] ?? '' }} </x-textarea-input>
                                </div>
                            </div>
                        </div>
                    </x-form>
                </div>
            </div>
        </div>
        <x-ad-slot class="mt-3" :advertisement="get_advert_model('below-form')" />
    </x-tool-wrapper>
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
