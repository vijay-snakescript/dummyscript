<x-application-category-wrapper>
    <x-ad-slot :advertisement="get_tools_page_advert_model()" />
    <x-page-wrapper :title="$category->name" :sub-title="$category->meta_description" heading="h1">
        <div class="products">
            <div class="row">
                <ul class="items {{ theme_option('tools_layout', 'grid-view') }}">
                    @foreach ($category->tools as $tool)
                        <x-tool-item :tool="$tool" />
                    @endforeach
                </ul>
            </div>
        </div>
    </x-page-wrapper>
    <x-ad-slot :advertisement="get_tools_page_advert_model()" />
    @if (!empty($category->description))
        <x-page-wrapper :title="$category->title">
            {!! $category->description !!}
        </x-page-wrapper>
    @endif
</x-application-category-wrapper>
