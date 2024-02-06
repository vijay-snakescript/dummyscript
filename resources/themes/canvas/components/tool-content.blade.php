@props(['tool'])
@if (setting('display_socialshare_icon', 1) == 1)
    <x-page-wrapper>
        <x-page-social-share element-classes="justify-content-between" style="style3" :url="route('tool.show', ['tool' => $tool->slug])"
            :title="$tool->meta_title" />
    </x-page-wrapper>
@endif
<x-ad-slot />
<x-page-wrapper>
    {!! $tool->content !!}
</x-page-wrapper>
