<x-canvas-layout>
    @if (!Widget::group('sidebar')->isEmpty())
        <x-slot name="sidebar">
            <x-application-sidebar>
                @widgetGroup('sidebar')
            </x-application-sidebar>
        </x-slot>
    @endif
    <h1 class="mb-3">{{ config('app.name') }}</h1>
    @if (setting('disable_favorite_tools', 1) == 1 && setting('homepage_favorite_tools', 1) == 1)
        <x-page-wrapper :title="__('tools.favoriteTools')" :sub-title="__('tools.favoriteToolsDesc')">
            <x-favorite-tools :tools="$favorties" />
        </x-page-wrapper>
    @endif
    @foreach ($tools as $item)
        @if ($loop->index % 3 == 0)
            <x-ad-slot :advertisement="get_advert_model(array_shift($ads))" />
        @endif
        <x-page-wrapper :title="$item->name" :sub-title="$item->meta_description" :link="route('tool.category', ['category' => $item->slug])">
            <div class="products">
                <div class="row">
                    <ul class="items {{ theme_option('tools_layout', 'grid-view') }}">
                        @foreach ($item->tools as $tool)
                            <x-tool-item :tool="$tool" />
                        @endforeach
                    </ul>
                </div>
            </div>
        </x-page-wrapper>
    @endforeach
</x-canvas-layout>
