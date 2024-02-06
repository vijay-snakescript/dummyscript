<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-tool-property-display :tool="$tool" name="fs_tool" label="maxFileSizeLimit" :plans="true"
            upTo="upTo100KB" />
        <x-form :route="route('tool.handle', $tool->slug)" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 mt-3 mb-3">
                    <x-upload-wrapper max-files="1" :max-size="$tool->fs_tool" accept=".png,.jpg,jpeg" input-name="image"
                        :file-title="__('tools.dropImageHereTitle')" :file-label="__('tools.dropImageHereLabel')" />
                </div>
            </div>
            <x-ad-slot class="mb-3" :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-outline-primary rounded-pill">@lang('common.convertNow')</button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')" :sub-title="$tool->description">
                <div class="tool-results result mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-shadow tabbar mb-3">
                                <div class="large-text-scroller printable-result">
                                    {!! nl2br($results['text']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <x-ad-slot :advertisement="get_advert_model('below-result')" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <div class="result-action-left">
                                    <x-copy-text :text="$results['text']" />
                                    <x-download-button :link="$results['download_url']" />
                                    <x-print-button id="printResult" />
                                </div>
                                <div class="result-action-right">
                                    <x-reload-button class="ms-auto" :link="route('tool.show', ['tool' => $tool->slug])" />
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
    @push('page_scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
                if (document.querySelector('#printResult')) {
                    document.querySelector('#printResult').addEventListener('click', () => {
                        ArtisanApp.printResult(document.querySelector('.printable-result'), {
                            title: "{{ $tool->name }}"
                        })
                    })
                }
            });
        </script>
    @endpush
</x-application-tools-wrapper>
