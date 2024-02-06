<x-application-blog-wrapper>
    <x-page-wrapper>
        <div class="single-content">
            <div class="post">
                @if ($post->getFirstMediaUrl('featured-image'))
                    <div class="blog-img box-shadow p-0">
                        <img class="img-fluid" src="{{ $post->getFirstMediaUrl('featured-image') }}"
                            alt="{{ $post->title }}">
                    </div>
                @endif
                <div class="content">
                    <h1 class="title">{{ $post->title }}</h1>
                    <div class="post-meta d-flex mb-3">
                        <div class="user-pic me-3">
                            @if ($post->author->getFirstMediaUrl('avatar'))
                                <img class="img-fluid" src="{{ $post->author->getFirstMediaUrl('avatar') }}"
                                    alt="Profile-Image">
                            @else
                                <img class="img-fluid" src="{{ setting('default_user_image') }}" alt="Profile-Image">
                            @endif
                        </div>
                        <div class="vcard">
                            <span class="d-block">
                                {{ $post->author->name }}
                                @foreach ($post->categories as $category)
                                    @if ($loop->index == 0)
                                        <span>@lang('common.in')</span>
                                    @endif
                                    @if ($loop->index > 0)
                                        <span>,</span>
                                    @endif
                                    <a href="{{ route('blog.category', $category->slug) }}">{{ $category->name }}</a>
                                @endforeach
                            </span>
                            <span class="date-read">{{ $post->created_at->format('M d') }} <span class="mx-1">/</span>
                                {{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <x-ad-slot :advertisement="get_advert_model('post-above')" />
                    {!! $post->contents !!}
                </div>
                @if ($post->tags->count() > 0)
                    <div class="categories pt-3 pb-3">
                        <span>@lang('admin.tags')</span>
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}"
                                class="badge bg-primary py-2 p-3 mb-2 rounded-pill">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                @endif
                <hr>
                <x-page-social-share :url="route('posts.show', ['slug' => $post->slug])" :title="$post->title" />
            </div>
        </div>
    </x-page-wrapper>
    <x-ad-slot :advertisement="get_advert_model('post-below')" />
</x-application-blog-wrapper>
