@if ($tags && $tags->count() > 0)
    <x-page-wrapper :title="$title">
        @foreach ($tags as $tag)
            <a class="btn btn-light text-light btn-sm rounded-pill me-1 mb-2" href="">{{ $tag->name }}</a>
        @endforeach
    </x-page-wrapper>
@endif
