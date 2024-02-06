@if ($posts && $posts->count() > 0)
    <x-page-wrapper :title="$title">
        <ul class="list-group list-group-flush">
            @foreach ($posts as $post)
                <li class="list-group-item">
                    <a href="{{ route('posts.show', ['slug' => $post->slug]) }}">
                        {{ $post->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </x-page-wrapper>
@endif
