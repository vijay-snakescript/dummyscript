<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label h4" for="url">@lang('tools.enterURL')</label>
                            <x-text-input type="text" class="form-control" name="url" id="url" required
                                value="{{ $results['url'] ?? old('url') }}" :placeholder="__('tools.enterWebsiteUrl')" :error="$errors->has('url')" />
                            <x-input-error :messages="$errors->get('url')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.researchKeyword')
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
                        <div class="col-md-12 table-responsive">
                            <table class="table table-style mb-0" id="">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('tools.keyword')</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    @foreach ($results['top'] as $top)
                                        <tr>
                                            <td class="text-start ps-3">{{ $loop->iteration }}</td>

                                            <td class="text-start ps-3">
                                                {{ $top['keyword'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />

</x-application-tools-wrapper>
