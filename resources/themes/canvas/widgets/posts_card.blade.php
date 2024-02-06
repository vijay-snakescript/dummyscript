@if ($posts && $posts->count() > 0)
    <x-page-wrapper :title="$title" class="blog-posts p-0 bg-transparent">
        <div class="grid-view">
            @foreach ($posts as $post)
                <div class="post item">
                    @if ($post->getFirstMediaUrl('featured-image'))
                        <div class="blog-img blog-sm box-shadow p-0">
                            <a href="{{ route('posts.show', ['slug' => $post->slug]) }}">
                                <img src="{{ $post->getFirstMediaUrl('featured-image') }}" alt="{{ $post->title }}"
                                    class="img-fluid rounded">
                            </a>
                        </div>
                    @endif
                    <div class="content p-2">
                        <h2 class="title">
                            <a href="{{ route('posts.show', ['slug' => $post->slug]) }}">
                                {{ $post->title }}
                            </a>
                        </h2>
                        @if (!empty($post->excerpt))
                            <p class="mb-3">{{ $post->excerpt }}</p>
                        @endif
                        <div class="post-meta">
                            <span
                                class="date-read">{{ $post->created_at->format(setting('joined_date_format', 'M d')) }}
                                <span class="mx-1">/</span> {{ $post->created_at->diffForHumans() }} </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-page-wrapper>
@endif
