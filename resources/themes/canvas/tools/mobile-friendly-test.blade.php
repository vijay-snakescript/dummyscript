<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <x-input-label for="url">@lang('tools.enterURL')</x-input-label>
                            <x-text-input type="text" class="form-control" name="url" id="url" required
                                value="{{ $results['url'] ?? old('url') }}" :placeholder="__('tools.enterWebsiteUrl')" :error="$errors->has('domain')" />
                            <x-input-error :messages="$errors->get('url')" class="mt-2" />
                        </div>
                    </div>

                </div>

            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.startTest')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result">
                    <div class="row my-4">
                        <div class="col-md-12">
                            <table class="table table-style mb-0">
                                <tbody>
                                    <tr>
                                        <td>
                                            @if ($results['mobileFriendly'])
                                                <h4 class="mb-0">@lang('tools.mobileFriendly')</h4>
                                            @else
                                                <h4 class="mb-0">@lang('tools.notMobileFriendly')</h4>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" target="_blank"
                                                href="https://search.google.com/test/mobile-friendly?url={{ $results['url'] }}">@lang('tools.moreResults')</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="d-flex justify-content-center website-screenshot-detail">
                                <div class="mobile">
                                    <div class="image">
                                        <div class="image-wrap">
                                            <img src="{{ generateScreenshotMobile($results['baseUrl']) }}"
                                                class="img-fluid screenshot" alt="{{ $tool->name }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
