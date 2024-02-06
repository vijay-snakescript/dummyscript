@props([
    'title' => false,
    'subTitle' => false,
    'heading' => 'h2',
    'link' => false,
    'heroClass' => null,
])

<div {!! $attributes->merge(['class' => 'wrap-content']) !!}>
    @if ($title || $subTitle)
        <div class="{{ trim($heroClass . ' hero-title') }} d-flex justify-content-between align-items-start">
            @if (!empty($title))
                <{{ $heading }}>
                    @if (!$link)
                        {{ $title }}
                    @else
                        <a href="{{ $link }}">
                            {{ $title }}
                        </a>
                    @endif
                    </{{ $heading }}>
            @endif
            @if (isset($actions))
                {{ $actions }}
            @endif
            @if (!empty($subTitle))
                <p>{{ $subTitle ?? '' }}</p>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
