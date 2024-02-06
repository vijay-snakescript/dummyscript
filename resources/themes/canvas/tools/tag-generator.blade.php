<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.siteTitle')</x-input-label>
                            <x-text-input class="form-control" placeholder="Site Title" name="site_title"
                                value="{{ $results['site_title'] ?? '' }}" :error="$errors->has('site_title')" required />
                            <x-input-error :messages="$errors->get('site_title')" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.siteDescription')</x-input-label>
                            <x-text-input class="form-control" placeholder="Site Description" name="site_description"
                                value="{{ $results['site_description'] ?? '' }}" :error="$errors->has('site_description')" required />
                            <x-input-error :messages="$errors->get('site_description')" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.siteKeywords')</x-input-label>
                            <x-text-input class="form-control" placeholder="Site,Keywords" name="site_keywords"
                                value="{{ $results['site_keywords'] ?? '' }}" :error="$errors->has('site_keywords')" required />
                            <x-input-error :messages="$errors->get('site_keywords')" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.allowIndex')</x-input-label>
                            <select class="form-control form-select @if ($errors->has('allow_index')) is-invalid @endif"
                                name="allow_index">
                                <option value="noindex" @if (isset($results) && $results['allow_index'] == 'noindex') selected @endif>No</option>
                                <option value="index" @if (isset($results) && $results['allow_index'] == 'index') selected @endif>Yes</option>
                            </select>
                            <x-input-error :messages="$errors->get('allow_index')" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.allowFollow')</x-input-label>
                            <select class="form-control form-select @if ($errors->has('allow_follow')) is-invalid @endif"
                                name="allow_follow">
                                <option value="nofollow" @if (isset($results) && $results['allow_follow'] == 'nofollow') selected @endif>No</option>
                                <option value="follow" @if (isset($results) && $results['allow_follow'] == 'follow') selected @endif>Yes</option>
                            </select>
                            <x-input-error :messages="$errors->get('allow_follow')" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.contentType')</x-input-label>
                            <select class="form-control form-select @if ($errors->has('content_type')) is-invalid @endif"
                                name="content_type">
                                <option value="utf-8" @if (isset($results) && $results['content_type'] == 'utf-8') selected @endif>UTF-8</option>
                                <option value="utf-16" @if (isset($results) && $results['content_type'] == 'utf-16') selected @endif>UTF-16</option>
                            </select>
                            <x-input-error :messages="$errors->get('content_type')" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.language')</x-input-label>
                            <select class="form-control form-select @if ($errors->has('language')) is-invalid @endif"
                                name="language">
                                <option value="English" @if (isset($results) && $results['language'] == 'English') selected @endif>English
                                </option>
                                <option value="French" @if (isset($results) && $results['language'] == 'French') selected @endif>French
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('language')" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <x-input-label>@lang('tools.revisit')</x-input-label>
                            <select
                                class="form-control form-select @if ($errors->has('days')) is-invalid @endif"
                                name="days">
                                <option value="0" @if (isset($results) && $results['days'] == '0') selected @endif>Select One
                                </option>
                                <option value="1" @if (isset($results) && $results['days'] == '1') selected @endif>1 Day
                                </option>
                                <option value="2" @if (isset($results) && $results['days'] == '2') selected @endif>2 Days
                                </option>
                                <option value="3" @if (isset($results) && $results['days'] == '3') selected @endif>3 Days
                                </option>
                                <option value="4" @if (isset($results) && $results['days'] == '4') selected @endif>4 Days
                                </option>
                                <option value="5" @if (isset($results) && $results['days'] == '5') selected @endif>5 Days
                                </option>
                                <option value="6" @if (isset($results) && $results['days'] == '6') selected @endif>6 Days
                                </option>
                                <option value="7" @if (isset($results) && $results['days'] == '7') selected @endif>7 Days
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('days')" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <x-input-label>@lang('tools.author')</x-input-label>
                            <x-text-input class="form-control" placeholder="Author" name="author"
                                value="{{ $results['author'] ?? '' }}" :error="$errors->has('author')" />
                            <x-input-error :messages="$errors->get('author')" />
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot class="mt-3" :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-primary rounded-pill">
                        @lang('common.generate')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results['converted_text']))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-shadow tabbar mb-3">
                                <div class="large-text-scroller printable-result html-entities" id="metaTags">
                                    {!! nl2br($results['converted_text']) !!}
                                </div>
                                <textarea aria-labelledby="{{ __('common.result') }}" class="d-none" id="save-to-file">{{ $results['normal_text'] }}</textarea>
                            </div>
                            <div class="result-copy mt-3 text-end">
                                <x-copy-target target="metaTags" :text="__('common.copyToClipboard')" :svg="false" />
                                <x-button class="btn btn-primary rounded-pill" type="button"
                                    onclick="ArtisanApp.downloadAsTxt('#save-to-file', {filename: '{{ $tool->slug . '.txt' }}'})">
                                    @lang('tools.saveAsTxt')
                                </x-button>
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
