@props([
    'search' => false,
    'searchPlaceholder' => __('common.searchStr'),
    'searchRoute' => null,
    'button' => __('common.createNew'),
    'button_class' => 'btn btn-outline-primary',
    'route' => null,
    'value' => request()->get('q'),
])

<div {{ $attributes->merge(['class' => 'align-items-center justify-content-between']) }}>
    <div class="row">
        <div class="col-md-6 col-sm-12 col-lg-3 mb-3">
            @if ($search)
                <form class="form-inline" action="{{ $searchRoute }}" method="get">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="{{ $searchPlaceholder }}"
                            value="{{ $value }}" autofocus>
                        <button type="submit" class="btn btn-primary text-white"><i
                                class="lni lni-search"></i></button>
                    </div>
                </form>
            @endif
        </div>
        <div class="col-md-6 col-sm-12 col-lg-9 text-end mb-3">
            @if ($route)
                <a href="{{ $route }}" class="{{ $button_class }}">{{ $button }}</a>
            @endif
            {{ $slot }}
        </div>
    </div>
</div>
