@props([
    'item' => null,
    'isChild' => false,
])
@if (!$isChild)
    <ol>
    @else
        <ul>
@endif
@foreach ($item as $index => $value)
    @if (empty($value))
        @continue;
    @endif
    <li>
        <div class="line-truncate line-1">
            <span class="fw-semibold">{{ $index }}</span>
            @if (is_array($value))
                <x-seo-og-schema :item="$value" :is-child="true" />
            @else
                {{ $value }}
            @endif
        </div>
    </li>
@endforeach
@if (!$isChild)
    </ol>
@else
    </ul>
@endif
