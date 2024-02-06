@if (!empty($text))
    <x-page-wrapper :title="$title">
        {!! nl2br(e($text)) !!}
    </x-page-wrapper>
@endif
