<x-form method="post" :route="route('front.index.action')" enctype="multipart/form-data">
    <div class="bg-light dark-mode-light-bg cssinput-wrap py-4">
        <div class="container">
            <div class="row cssinput-wrap">
                <div class="col-md-6 mb-3">
                    <h1>{{ $tool->name }}</h1>
                </div>
                <div class="col-md-12 mb-3">
                    <x-textarea-input class="cssmin-in form-control" rows="8" spellcheck="false" id="cssInput"
                        name="css" required>
                        {{ $results['css'] ?? '' }}</x-textarea-input>
                    <x-input-error :messages="$errors->get('css')" class="mt-2" />
                </div>
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-primary minify" id="minify">
                        {{ __('tools.minify') }}
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-form>
@if (isset($results))
    <div class="container-fluid bg-light dark-mode-light-bg py-4">
        <div class="container">
            <div class="tool-results-wrapper">
                <x-ad-slot :advertisement="get_advert_model('above-result')" />
                <x-page-wrapper :title="__('common.result')" class="mb-0">
                    <div class="result mt-4">
                        <div class="result tool-results">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <table class="table table-style">
                                        <thead>
                                            <th>@lang('tools.originalSize')</th>
                                            <th>@lang('tools.minifiedSize')</th>
                                            <th width="200">@lang('tools.saveSize')</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ formatSizeUnits($results['input_size']) }}</td>
                                                <td>{{ formatSizeUnits($results['output_size']) }}</td>
                                                <td>{{ formatSizeUnits($results['input_size'] - $results['output_size']) }}
                                                    ({{ $results['save_size'] }} %)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div class="custom-textarea p-3">
                                        <x-textarea-input id="minifiedCss" class="transparent" readonly rows="8">
                                            {{ $results['content'] }}
                                        </x-textarea-input>
                                    </div>
                                    <div class="result-copy mt-3 text-end">
                                        <x-copy-target target="minifiedCss" :text="__('common.copyToClipboard')" :svg="false" />
                                        <x-download-form-button type="button" id="btn-download-file"
                                            :tooltip="__('tools.downloadCss')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-page-wrapper>
            </div>
        </div>
    </div>
@endif
