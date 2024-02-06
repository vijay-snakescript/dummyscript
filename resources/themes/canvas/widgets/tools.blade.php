@if ($tools->count() > 0)
    <x-page-wrapper :title="$title">
        <ul class="list-group list-group-flush">
            @foreach ($tools as $tool)
                <li class="list-group-item"><a
                        href="{{ route('tool.show', ['tool' => $tool->slug]) }}">{{ $tool->name }}</a></li>
            @endforeach
        </ul>
    </x-page-wrapper>
@endif
