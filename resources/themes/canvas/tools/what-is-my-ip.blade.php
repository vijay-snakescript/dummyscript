<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12 mt-2 mb-3">
                    <div class="text-center px-3 py-5 border bg-light">
                        <div class="h4">@lang('tools.yourPublicIPAdd')</div>
                        <div class="display-6 line-truncate line-1">{{ $content['ip'] ?? $ip }}</div>
                        @if ($content)
                            <div class="h3 mb-0">{{ $content['country'] }}, {{ $content['city'] }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 d-flex justify-content-between">
                    <x-copy-text :text="$content['ip'] ?? $ip" />
                    <x-button type="submit" class="btn btn-primary rounded-pill">
                        @lang('tools.showMoreDetails')
                    </x-button>
                </div>
            </div>
        </x-form>
        <x-ad-slot class="mt-3" :advertisement="get_advert_model('below-form')" />
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4">
                    <x-ad-slot :advertisement="get_advert_model('above-result')" />
                    <div class="row">
                        @if (isset($tool->settings->show_map) && $tool->settings->show_map == 1)
                            <div class="col-md-12">
                                <iframe
                                    src="https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&ie=UTF8&iwloc=A&output=embed&z=6&q={{ $content['city'] ?? '' }},{{ $content['region'] ?? '' }},Pakistan"
                                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <table class="table table-style mb-0">
                                <tbody>
                                    @foreach ($content as $language => $item)
                                        @if (!empty($item))
                                            <tr>
                                                <th>{{ __("tools.${language}") }}</th>
                                                <td>
                                                    <div class="text-break">
                                                        {{ $item }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <x-copy-text :text="$item" />
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
