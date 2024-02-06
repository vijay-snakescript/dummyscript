@props([
    'tool' => null,
    'name' => null,
    'label' => null,
    'plans' => false,
    'upTo' => null,
])
<div class="col-md-12 mb-3 align-content-center d-flex">
    <div {{ $attributes->merge(['class' => 'me-auto align-self-center']) }}>
        @if (Lang::has("tools.{$label}"))
            <span class="text-primary"> @lang("tools.{$label}", ['count' => $tool->$name])</span>
        @endif
    </div>
    @if ($plans)
        <div {{ $attributes->merge(['class' => 'ms-auto text-end']) }}>
            @if (Lang::has("tools.{$upTo}"))
                <span class="px-2">@lang("tools.{$upTo}")</span>
            @endif
            <a href="{{ route('plans.list') }}" class="btn btn-primary btn-sm rounded-pill" type="button"
                id="button">@lang('tools.goPro')</a>
        </div>
    @endif
</div>
