<header class="header header-sticky mb-4">
    <div class="container-fluid">
        <button class="header-toggler px-md-0 me-md-3" type="button"
            onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
            <i class="icon icon-lg lni lni-menu"></i>
        </button>
        <a class="header-brand d-md-none" href="{{ route('admin.dashboard') }}">
            <x-application-logo class="sidebar-brand-full" width="168" height="30"
                alt="{{ config('app.name', 'Monster Tools') }}" />
        </a>
        <ul class="header-nav d-none d-md-flex">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.users') }}">@lang('admin.users')</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings') }}">@lang('admin.settings')</a></li>
        </ul>
        <ul class="header-nav ms-auto"></ul>
        <ul class="header-nav ms-3">
            <li class="nav-item dropdown"><a class="nav-link py-0" data-coreui-toggle="dropdown" href="#"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md">
                        @if (Auth::user()->getFirstMediaUrl('avatar'))
                                <img class="avatar-img" src="{{ Auth::user()->getFirstMediaUrl('avatar') }}"
                                    alt="{{ Auth::user()->name }}">
                            @else
                                <img class="avatar-img" src="{{ setting('default_user_image') }}"
                                    alt="{{ Auth::user()->name }}">
                            @endif
                        </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <a class="dropdown-item" href="{{ route('front.index') }}" target="_blank">
                        <i class="lni lni-home icon me-2"></i> @lang('admin.visitSite')</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="lni lni-user icon me-2"></i> @lang('profile.profile')</a>
                    <a class="dropdown-item" href="{{ route('admin.password') }}">
                        <i class="lni lni-lock-alt me-2"></i> @lang('common.changePassword')</a>
                    <a class="dropdown-item" href="{{ route('admin.mfa', Auth()->user()) }}">
                        <i class="lni lni-protection me-2"></i> @lang('profile.2faDescription')</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="lni lni-exit me-2"></i> @lang('auth.signout')</a>
                </div>
            </li>
        </ul>
    </div>
    <div class="header-divider"></div>
    <div class="container-fluid">
        {{ Breadcrumbs::render() }}
    </div>
</header>
