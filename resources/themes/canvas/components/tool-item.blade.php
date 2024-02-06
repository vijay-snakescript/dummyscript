@props(['tool'])
<li>
    <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}">
        @if ($tool->icon_type == 'class')
            <i class="an-duotone an-{{ $tool->icon_class }}"></i>
        @elseif ($tool->getFirstMediaUrl('tool-icon'))
            <img src="{{ $tool->getFirstMediaUrl('tool-icon') }}" alt="{{ $tool->name }}">
        @endif
        <span>{{ $tool->name }}</span>
    </a>
</li>
