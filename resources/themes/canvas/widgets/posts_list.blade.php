@if ($posts && $posts->count() > 0)
    <x-page-wrapper :title="$title" class="blog-widget">
        @foreach ($posts as $post)
            <div class="post">
                @if ($post->getFirstMediaUrl('featured-image'))
                    <div class="blog-img shadow-sm me-3">
                        <a href="{{ route('posts.show', ['slug' => $post->slug]) }}">
                            <img src="{{ $post->getFirstMediaUrl('featured-image') }}" alt="{{ $post->title }}"
                                class="img-fluid rounded">
                        </a>
                    </div>
                @endif
                <div class="content">
                    <h5 class="post-title line-truncate line-2">
                        <a href="{{ route('posts.show', ['slug' => $post->slug]) }}">
                            {{ $post->title }}
                        </a>
                    </h5>
                    <div class="post-meta">
                        <span class="date-read">{{ $post->created_at->format(setting('joined_date_format', 'M d')) }}
                            <span class="mx-1">/</span> {{ $post->created_at->diffForHumans() }} </span>
                    </div>
                </div>
            </div>
        @endforeach
    </x-page-wrapper>
@endif
