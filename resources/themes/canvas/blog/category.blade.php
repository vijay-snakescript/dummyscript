<x-application-blog-wrapper>
    <x-page-wrapper class="bg-transparent" :title="$category->name" :sub-title="$category->description" heading="h1">
        <div class="row blog-posts">
            @foreach ($posts as $post)
                @php
                    $dynamic_class = $loop->index > 0 && ($loop->iteration % 7 == 6 || $loop->iteration % 7 == 0) ? 'list-view' : 'grid-view';
                    $columns = $loop->index > 0 && in_array($loop->iteration % 7, [2, 3, 4, 5]) ? 6 : 12;
                @endphp
                <div class="col-md-{{ $columns }} {{ $dynamic_class }}">
                    <div class="post">
                        @if ($post->getFirstMediaUrl('featured-image'))
                            <div class="blog-img box-shadow p-0">
                                <a href="{{ route('posts.show', ['slug' => $post->slug]) }}">
                                    <img src="{{ $post->getFirstMediaUrl('featured-image') }}" alt="{{ $post->title }}">
                                </a>
                            </div>
                        @endif
                        <div class="content">
                            <h2 class="title">
                                <a href="{{ route('posts.show', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="mb-3">{{ $post->excerpt }}</p>
                            <div class="post-meta">
                                <span class="d-block">
                                    {{ $post->author->name }}
                                    @foreach ($post->categories as $category)
                                        @if ($loop->index == 0)
                                            <span>@lang('common.in')</span>
                                        @endif
                                        @if ($loop->index > 0)
                                            <span>,</span>
                                        @endif
                                        <a
                                            href="{{ route('blog.category', $category->slug) }}">{{ $category->name }}</a>
                                    @endforeach
                                </span>
                                <span class="date-read">{{ $post->created_at->format('M d') }} <span
                                        class="mx-1">/</span>
                                    {{ $post->created_at->diffForHumans() }} </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-page-wrapper>
</x-application-blog-wrapper>
