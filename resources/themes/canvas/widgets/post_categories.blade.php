@if ($categories && $categories->count() > 0)
    <x-page-wrapper :title="$title">
        <ul class="list-inline list{{ isset($config['style']) && $config['style'] == 1 ? '' : '-row' }}">
            @foreach ($categories as $index => $category)
                @if (empty($category->slug))
                    @continue
                @endif
                <li>
                    <a href="{{ route('blog.category', $category->slug) }}">
                        {{ $category->name }}
                        @if (isset($config['post_counts']) && $config['post_counts'])
                            <span class="text-muted">({{ $category->posts_count ?? '' }})</span>
                        @endif
                    </a>
                </li>
                @if ($category->relationLoaded('children'))
                    @foreach ($category->children as $index => $child)
                        @if (empty($child->slug))
                            @continue
                        @endif
                        <li class="list-children">
                            <a href="{{ route('post.category', $child->slug) }}">
                                {{ $child->name }}
                                @if (isset($config['post_counts']) && $config['post_counts'])
                                    <span class="text-muted">({{ $category->posts_count ?? '' }})</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>
    </x-page-wrapper>
@endif
