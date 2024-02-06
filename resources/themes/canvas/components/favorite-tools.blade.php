@props(['tools'])
<div class="products">
    <div class="row">
        @guest
            <div class="col-md-12">
                <div class="favorite-tools text-center">
                    <h6 class="p-2">@lang('tools.favoriteToolsGuestDesc')</h6>
                    <a class="btn btn-primary rounded-pill ps-5 pe-5 mt-3" href="{{ route('login') }}">@lang('auth.login')</a>
                </div>
            </div>
        @elseif($tools)
            <ul class="items {{ theme_option('tools_layout', 'grid-view') }}">
                @foreach ($tools as $tool)
                    <x-tool-item :tool="$tool" />
                @endforeach
            </ul>
        @else
            <div class="col-md-12">
                <div class="favorite-tools text-center">
                    <h6 class="p-2">@lang('tools.favoriteToolsAuthDesc')</h6>
                </div>
            </div>
        @endguest
    </div>
</div>
