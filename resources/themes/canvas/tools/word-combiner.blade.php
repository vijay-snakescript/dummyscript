<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row mt-4 mb-2">
            </div>
            <div class="row">
                <div class="col-md-3 mt-2 mb-3">
                    <x-input-label>@lang('tools.prePhase')</x-input-label>
                    <x-text-input class="form-control" placeholder="pre-phase" name="pre-phase"
                        value="{{ $results['pre_phrase'] ?? '' }}" />
                    <x-input-error :messages="$errors->get('pre-phase')" class="mt-2" />
                </div>
                <div class="col-md-3 mt-2 mb-3">
                    <x-input-label>@lang('tools.postPhase')</x-input-label>
                    <x-text-input class="form-control" placeholder="post-phase" name="post-phase"
                        value="{{ $results['post_phrase'] ?? '' }}" />
                    <x-input-error :messages="$errors->get('post-phase')" class="mt-2" />
                </div>
                <div class="col-md-3 mt-2 mb-3">
                    <x-input-label>@lang('tools.seperator')</x-input-label>
                    <select class="form-control form-select" name="seperator">
                        <option value="" @if (isset($results) && $results['seperator'] == '') selected @endif>Nothing</option>
                        <option value=" " @if (isset($results) && $results['seperator'] == ' ') selected @endif>Space</option>
                        <option value="." @if (isset($results) && $results['seperator'] == '.') selected @endif>.</option>
                        <option value="," @if (isset($results) && $results['seperator'] == ',') selected @endif>,</option>
                        <option value="+" @if (isset($results) && $results['seperator'] == '+') selected @endif>+</option>
                        <option value="-" @if (isset($results) && $results['seperator'] == '-') selected @endif>-</option>
                    </select>
                    <x-input-error :messages="$errors->get('seperator')" class="mt-2" />
                </div>
                <div class="col-md-3 mt-2 mb-3">
                    <x-input-label>@lang('tools.wrapIn')</x-input-label>
                    <select class="form-control form-select" name="wrap-in">
                        <option value="1" @if (isset($results) && $results['wrap_in'] == '1') selected @endif>Nothing</option>
                        <option value="2" @if (isset($results) && $results['wrap_in'] == '2') selected @endif>()</option>
                        <option value="3" @if (isset($results) && $results['wrap_in'] == '3') selected @endif>" "</option>
                        <option value="4" @if (isset($results) && $results['wrap_in'] == '4') selected @endif>' '</option>
                        <option value="5" @if (isset($results) && $results['wrap_in'] == '5') selected @endif>[]</option>
                    </select>
                    <x-input-error :messages="$errors->get('post-phase')" class="mt-2" />
                </div>
                <div class="col-md-4 mt-2 mb-3">
                    <div class="form-group">
                        <x-textarea-input type="text" name="string_first" class="form-control" rows="8"
                            :placeholder="__('common.someText')" id="textarea" required autofocus contenteditable="true">
                            @if (isset($results))
                                {{ $results['string_first'] }}
                            @endif
                        </x-textarea-input>
                        <x-input-error :messages="$errors->get('string_first')" class="mt-2" />
                    </div>
                </div>
                <div class="col-md-4 mt-2 mb-3">
                    <div class="form-group">
                        <x-textarea-input type="text" name="string_second" class="form-control" rows="8"
                            :placeholder="__('common.someText')" id="textarea" autofocus contenteditable="true">
                            @if (isset($results))
                                {{ $results['string_second'] }}
                            @endif
                        </x-textarea-input>
                        <x-input-error :messages="$errors->get('string_second')" class="mt-2" />
                    </div>
                </div>
                <div class="col-md-4 mt-2 mb-3">
                    <div class="form-group">
                        <x-textarea-input type="text" name="string_third" class="form-control" rows="8"
                            :placeholder="__('common.someText')" id="textarea" autofocus contenteditable="true">
                            @if (isset($results))
                                {{ $results['string_third'] }}
                            @endif
                        </x-textarea-input>
                        <x-input-error :messages="$errors->get('string_third')" class="mt-2" />
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.combineWords')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')" :sub-title="trans_choice('tools.countCombinationMerged', $results['merged_total'])">
                <div class="result mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-shadow tabbar mb-3 custom-textarea">
                                <x-textarea-input type="text" id="word-combiner-result"
                                    class="form-control transparent" rows="8">
                                    {{ $results['converted_text'] }}
                                </x-textarea-input>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-copy-target target="word-combiner-result" />
                                    <x-download-form-button type="button"
                                        onclick="ArtisanApp.downloadAsTxt('#rword-combiner-result', {filename: '{{ $tool->slug . '.txt' }}'})"
                                        :tooltip="__('tools.saveAsTxt')" />
                                    <x-print-button
                                        onclick="ArtisanApp.printResult(document.querySelector('#rword-combiner-result'), {title: '{{ $tool->name }}'})"
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
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
