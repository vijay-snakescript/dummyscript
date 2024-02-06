<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <x-input-label>@lang('tools.enterWebsiteUrl')</x-input-label>
                        <x-text-input class="form-control" name="url" id="url" type="url" required
                            value="{{ $url ?? old('url') }}" :placeholder="__('tools.enterOrPasteUrl')" />
                        <x-input-error :messages="$errors->get('url')" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <x-input-label for="view_width">@lang('tools.screenSize')</x-input-label>
                        <select name="view_width" id="view_width" class="form-select">
                            <option value="1920">Desktop HD (1920px)</option>
                            <option value="1440">Desktop (1440px)</option>
                            <option value="768">Tablet (768px)</option>
                            <option value="320">Mobile (320px)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <x-input-label for="page_size">@lang('tools.pageSize')</x-input-label>
                        <select name="page_size" id="page_size" class="form-select">
                            <option value="A3">A3 (297x420 mm)</option>
                            <option value="A4" selected="">A4 (297x210 mm)</option>
                            <option value="A5">A5 (148x210 mm)</option>
                            <option value="Letter">US Letter (216x279 mm)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <x-input-label for="page_orientation">@lang('tools.orientation')</x-input-label>
                        <select name="page_orientation" id="page_orientation" class="form-select">
                            <option value="portrait" @if (old('page_orientation', 'portrait') == 'portrait') selected @endif>@lang('tools.portrait')
                            </option>
                            <option value="landscape" @if (old('page_orientation', 'portrait') == 'landscape') selected @endif>
                                @lang('tools.landscape')
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <x-input-label for="page_margin">@lang('tools.pageMargin')</x-input-label>
                        <select name="page_margin" id="page_margin" class="form-select">
                            <option value="0" @if (old('page_orientation', '0') == '0') selected @endif>
                                @lang('tools.noMargin')
                            </option>
                            <option value="20" @if (old('page_orientation', '0') == '20') selected @endif>
                                @lang('tools.smallMargin')
                            </option>
                            <option value="40" @if (old('page_orientation', '0') == '40') selected @endif>
                                @lang('tools.bigMargin')
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check mb-3">
                        <input type="checkbox" name="single_page" id="single_page" class="form-check-input"
                            value="1" checked>
                        <label class="form-check-label" for="single_page">@lang('tools.oneLongPage')</label>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.convertToPdf')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-style">
                            <thead>
                                <tr>
                                    <th width="75">#</th>
                                    <th>@lang('common.fileName')</th>
                                    <th width="200">@lang('common.size')</th>
                                    <th width="150"></th>
                                </tr>
                            </thead>
                            <tbody id="processing-files">
                                @foreach ($results['files'] as $file)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="mw-350 text-truncate fw-bold">{{ $file['filename'] }}</div>
                                        </td>
                                        <td>{{ formatSizeUnits($file['size']) }}</td>
                                        <td id="file-cursor">
                                            <button class="btn btn-outline-primary rounded-circle download-file-btn"
                                                data-bs-toggle="tooltip" title="@lang('common.download')" type="button"
                                                id="download-file" data-url="{{ $file['url'] }}"
                                                data-filename="{{ $file['filename'] }}">
                                                <i class="an an-long-arrow-down"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 text-end">
                        @if (count($results['files']) > 1)
                            <x-form class="no-app-loader download-all-btn d-inline-block" method="post"
                                :route="route('tool.postAction', [
                                    'tool' => $tool->slug,
                                    'action' => 'download-all',
                                ])">
                                <input type="hidden" name="process_id" value="{{ $results['process_id'] }}">
                                <x-download-form-button :text="__('tools.downloadAll')" />
                            </x-form>
                        @endif
                        <x-reload-button :link="route('tool.show', ['tool' => $tool->slug])" />
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
        @push('page_scripts')
            <script>
                const APP = function() {
                    const process_id = '{{ $results['process_id'] }}',
                        attachEvents = function() {
                            document.querySelectorAll('.download-file-btn').forEach(button => {
                                button.addEventListener('click', e => {
                                    const element = e.target.classList.contains('.download-file-btn') ? e
                                        .target : e.target.closest('.download-file-btn')
                                    ArtisanApp.downloadFromUrl(element.dataset.url, element.dataset
                                        .filename)
                                })
                            });
                        };
                    return {
                        init: function() {
                            attachEvents();
                        },
                    }
                }();
                document.addEventListener("DOMContentLoaded", function(event) {
                    APP.init();
                });
            </script>
        @endpush
    @endif
</x-application-tools-wrapper>
