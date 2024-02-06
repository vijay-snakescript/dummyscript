@php
    $favourites = Auth::check() ? Auth::user()->favorite_tools : null;
@endphp
@if ($favourites && $favourites->count() > 0)
    <li class="nav-item">
        <div id="favMenu" class="fav-menu">
            <div class="nav-link fav-btn ms-lg-2" data-bs-toggle="dropdown">
                <i class="an an-grid-layouts"></i>
                <span>@lang('tools.favoriteTools')</span>
            </div>
            <div id="favrorite-tools" class="menu-items box-shadow p-0 dropdown-menu dropdown-menu-end">
                <div class="icons-container">
                    @foreach ($favourites as $tool)
                        <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}" class="menu-link bg-hover"
                            data-bs-toggle="tooltip" title="{{ $tool->name }}">
                            @if ($tool->icon_type == 'class')
                                <i class="an-duotone an-{{ $tool->icon_class }}"></i>
                            @elseif ($tool->getFirstMediaUrl('tool-icon'))
                                <img src="{{ $tool->getFirstMediaUrl('tool-icon') }}" alt="{{ $tool->name }}">
                            @endif
                            <span>{{ $tool->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </li>
@endif
