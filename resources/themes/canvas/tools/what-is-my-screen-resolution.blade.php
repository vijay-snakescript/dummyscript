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
                        @lang('tools.checkResolution')
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
                        <div class="col-md-3">
                            <x-input-label for="">@lang('tools.desktops')</x-input-label>
                            <select class="form-control screens">
                                <option value="0">@lang('tools.selectOne')</option>
                                @foreach ($results['desktops'] as $key => $desktop)
                                    <option value="{{ $key }}">{{ $desktop }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <x-input-label for="">@lang('tools.tablets')</x-input-label>
                            <select class="form-control screens">
                                <option value="0">@lang('tools.selectOne')</option>
                                @foreach ($results['tablets'] as $key => $tablet)
                                    <option value="{{ $key }}">{{ $tablet }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <x-input-label for="">@lang('tools.mobiles')</x-input-label>
                            <select class="form-control screens">
                                <option value="0">@lang('tools.selectOne')</option>
                                @foreach ($results['mobiles'] as $key => $mobile)
                                    <option value="{{ $key }}">{{ $mobile }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <x-input-label for="">@lang('tools.televisions')</x-input-label>
                            <select class="form-control screens">
                                <option value="0">@lang('tools.selectOne')</option>
                                @foreach ($results['televisions'] as $key => $television)
                                    <option value="{{ $key }}">{{ $television }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 postion-relative overflow-auto">
                            <iframe id="screenFrame" name="myiframe" class="mx-auto d-block p-3 bg_2f3133"
                                scrolling="yes" height="800" width="1280" src="{{ $results['url'] }}"></iframe>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {

                const setResolution = function() {
                        const screens = document.querySelectorAll('.screens');
                        const iframe = document.getElementById('screenFrame');
                        screens.forEach(screen => {
                            screen.addEventListener('click', function handleClick(event) {
                                if (screen.value == 0) {
                                    return;
                                }
                                var size = screen.value.split("x");
                                iframe.height = parseInt(size[1]);
                                iframe.width = parseInt(size[0]);
                                setUserAgent(iframe, "Mobile Agent");
                            });
                        });
                    },
                    setUserAgent = function(window, userAgent) {
                        if (window.navigator.userAgent != userAgent) {
                            var userAgentProp = {
                                get: function() {
                                    return userAgent;
                                }
                            };
                            try {
                                Object.defineProperty(window.navigator, 'userAgent', userAgentProp);
                            } catch (e) {
                                window.navigator = Object.create(navigator, {
                                    userAgent: userAgentProp
                                });
                            }
                        }
                    };
                return {
                    init: function() {
                        setResolution();
                    }
                }
            }();

            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
