<x-application-page-wrapper>
    <x-page-wrapper :title="$page->title" :sub-title="$page->excerpt" heading="h1">
        {!! $page->content !!}
    </x-page-wrapper>
</x-application-page-wrapper>
