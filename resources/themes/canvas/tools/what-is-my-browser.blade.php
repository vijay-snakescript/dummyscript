<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-style mb-0">
                        <tbody id="results-container1">
                            <tr>
                                <th>@lang('tools.yourBrowser')</th>
                                <td class="text-start ps-3">{{ $result['browser'] }}</td>
                            </tr>
                            <tr>
                                <th>@lang('tools.browserVrsion')</th>
                                <td class="text-start ps-3">{{ $result['version'] }}</td>
                            </tr>
                            <tr>
                                <th>@lang('tools.userAgent')</th>
                                <td class="text-start ps-3">{{ $result['agent'] }}</td>
                            </tr>
                            <tr>
                                <th>@lang('tools.platform')</th>
                                <td class="text-start ps-3">{{ $result['platform'] }}</td>
                            </tr>
                            <tr>
                                <th>@lang('tools.languages')</th>
                                <td class="text-start ps-3">{{ $result['languages'] }}</td>
                            </tr>
                            <tr>
                                <th>@lang('tools.cookies')</th>
                                <td class="text-start ps-3">
                                    {{ Cookie::has('siteMode') ? __('tools.cookieEnable') : __('tools.cookieDisable') }}
                                </td>
                            </tr>
                            <tr>
                                <th>@lang('tools.screen')</th>
                                <td class="text-start ps-3">
                                    <ul class="ps-3 mb-0" id="screen"></ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
        </x-form>
    </x-tool-wrapper>
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const getScreen = function() {
                    var height = window.screen.height;
                    var width = window.screen.width;
                    var browser_w = window.innerWidth;
                    var browser_h = window.innerHeight
                    var depth = screen.colorDepth;

                    document.getElementById('screen').innerHTML =
                        `<li>{{ __('tools.screen') }}: ${width} x ${height}</li>
                        <li>{{ __('tools.browserScreen') }}: ${browser_w} x ${browser_h}</li>
                        <li>{{ __('tools.screenColorDepth') }}: ${depth}</li>`;
                };

                return {
                    init: function() {
                        getScreen();
                    }
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
