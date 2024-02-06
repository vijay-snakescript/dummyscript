<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow py-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.type')</x-input-label>
                            <select class="form-control form-select" id="type" name="type">
                                <option value="app">App</option>
                                <option value="player">Player</option>
                                <option value="product">Product</option>
                                <option value="summary">Summary</option>
                                <option value="summary_large_image">Summary with Large image</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.siteUsername')</x-input-label>
                            <x-text-input class="form-control text-content" placeholder="@" name="site_user_name"
                                id="site_user_name" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.appName')</x-input-label>
                            <x-text-input class="form-control text-content" placeholder="App Name" name="app_name"
                                id="app_name" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.iphoneAppId')</x-input-label>
                            <x-text-input class="form-control text-content" name="iphone_app_id" id="iphone_app_id"
                                required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.ipadAppId')</x-input-label>
                            <x-text-input class="form-control text-content" name="ipad_app_id" id="ipad_app_id"
                                required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.googlePlayId')</x-input-label>
                            <x-text-input class="form-control text-content" name="google_play_id" id="google_play_id"
                                required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.appCountry')</x-input-label>
                            <x-text-input class="form-control text-content" name="app_country" id="app_country"
                                required />
                        </div>
                    </div>
                    <div class="col-md-12 d-none" id="twitterImage">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.imageUrl')</x-input-label>
                            <x-text-input class="form-control text-content" name="image_url" id="image_url" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('common.description')</x-input-label>
                            <x-textarea-input type="text" name="description" class="form-control text-content"
                                rows="8" :placeholder="__('common.someText')" id="description" required>
                            </x-textarea-input>
                        </div>
                    </div>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <x-ad-slot :advertisement="get_advert_model('below-form')" />
    <x-page-wrapper :title="__('common.result')">
        <div class="result mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-shadow tabbar mb-3">
                        <div class="large-text-scroller printable-result html-entities" id="cardResults"></div>
                    </div>
                    <div class="result-copy mt-3 text-end">
                        <x-copy-target target="cardResults" :text="__('common.copyToClipboard')" :svg="false" />
                    </div>
                </div>
            </div>
        </div>
    </x-page-wrapper>
    <x-tool-content :tool="$tool" />
    <script>
        const APP = function() {
            const items = document.querySelectorAll('.text-content');
            const imageCL = document.getElementById('twitterImage').classList;
            const attachEvents = function() {
                    document.getElementById('type').addEventListener('change', (e) => {
                        isImage()
                        setTag();
                    });
                    items.forEach(element => {
                        element.addEventListener('change', () => {
                            setTag();
                        });
                    });

                    setTag()
                    isImage()
                },
                isImage = function() {
                    const type = document.getElementById("type").value;
                    (type == 'summary' || type ==
                        'summary_large_image' || type == 'player') ? imageCL.remove('d-none'): imageCL.add(
                        'd-none')
                },
                setTag = function() {
                    let html = '';
                    const type = document.getElementById("type").value
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:card"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                        type + '"</span>&gt;<br>'

                    if (type == 'summary' || type == 'summary_large_image' || type == 'player') {
                        html +=
                            '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:image"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                            document.getElementById('image_url').value + '"</span>&gt;<br>'
                    }
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:site"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"@' +
                        document.getElementById("site_user_name").value + '"</span>&gt;<br>'
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:description"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                        document.getElementById("description").value + '"</span>&gt;<br>'
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:app:name:iphone"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                        document.getElementById("app_name").value + '"</span>&gt;<br>'
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:app:id:iphone"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                        document.getElementById("iphone_app_id").value + '"</span>&gt;<br>'
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:app:name:ipad"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                        document.getElementById("app_name").value + '"</span>&gt;<br>'
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:app:id:ipad"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                        document.getElementById("ipad_app_id").value + '"</span>&gt;<br>'
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:app:name:googleplay"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                        document.getElementById("app_name").value + '"</span>&gt;<br>'
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:app:id:googleplay"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                        document.getElementById("google_play_id").value + '"</span>&gt;<br>'
                    html +=
                        '&lt;<span class="tag_name">meta</span> <span class="tag_attr">name=</span><span class="tag_attr_value">"twitter:app:country"</span> <span class="tag_attr">content=</span><span class="tag_attr_value">"' +
                        document.getElementById("app_country").value + '"</span>&gt;'

                    document.getElementById('cardResults').innerHTML = html
                };

            return {
                init: function() {
                    attachEvents()
                }
            };
        }();
        document.addEventListener("DOMContentLoaded", function(event) {
            APP.init();
        });
    </script>
</x-application-tools-wrapper>
