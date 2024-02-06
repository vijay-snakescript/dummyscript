<x-page-wrapper :title="$title" class="{{ $menu_style == 'inline' ? 'bg-transparent p-0' : 'full-widget' }}">
    {!! menu($menu_id, $menu_style) !!}
</x-page-wrapper>
