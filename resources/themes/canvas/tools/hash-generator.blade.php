<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <x-textarea-input type="text" name="string" class="mb-3" required autofocus>
                {{ $string ?? '' }}
            </x-textarea-input>
            <x-input-error :messages="$errors->get('string')" class="mt-2" />
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="text-end">
                <x-button type="submit" class="btn-outline-primary rounded-pill">
                    @lang('common.generate')
                </x-button>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <x-ad-slot :advertisement="get_advert_model('above-result')" />
        <x-page-wrapper>
            <table class="table table-style mb-0">
                <tbody>
                    @foreach ($results as $item)
                        <tr>
                            <th>{{ $item['label'] }}</th>
                            <td>
                                <div class="text-break">
                                    {{ $item['value'] }}
                                </div>
                            </td>
                            <td>
                                <x-copy-text :text="$item['value']" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-page-wrapper>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
