@props([
    'title' => false,
    'subTitle' => false,
    'heading' => 'h2',
    'heroClass' => '',
])
<x-application-no-widget-wrapper>
    <x-page-wrapper>
        <div class="row">
            <div class="col-md-12">
                <div class="profile">
                    <div class="user mb-3">
                        <div class="profile-image mx-auto">
                            @if (Auth::user()->getFirstMediaUrl('avatar'))
                                <img class="image" src="{{ Auth::user()->getFirstMediaUrl('avatar') }}"
                                    alt="Profile-Image">
                            @else
                                <img class="image" src="{{ setting('default_user_image') }}"
                                    alt="Profile-Image">
                            @endif
                            <a href="#">
                                <i class="an an-image stroke-transparent"></i>
                            </a>
                        </div>
                        <div class="name text-center mt-2">
                            <h4 class="mb-0">{{ Auth::user()->name }}</h4>
                            <p>{{ Auth::user()->email }}</p>
                        </div>
                        <ul class="list-group list-group-flush mt-4" role="navigation">
                            <li class="list-group-item {{ active(['user.profile'], 'active-link', 'user-profile') }}">
                                <a href="{{ route('user.profile') }}">
                                    @lang('profile.profile')
                                </a>
                            </li>
                            <li class="list-group-item {{ active(['user.password'], 'active-link', 'user-profile') }}">
                                <a href="{{ route('user.password') }}">
                                    @lang('profile.passwordDescription')
                                </a>
                            </li>
                            <li class="list-group-item {{ active(['user.twofactor'], 'active-link', 'user-profile') }}">
                                <a href="{{ route('user.twofactor') }}">
                                    @lang('profile.2faDescription')
                                </a>
                            </li>
                            <li class="list-group-item {{ active(['user.plan'], 'active-link', 'user-profile') }}">
                                <a href="{{ route('user.plan') }}">
                                    @lang('profile.plan')
                                </a>
                            </li>
                            <li
                                class="list-group-item {{ active(['payments.transactions'], 'active-link', 'user-profile') }}">
                                <a href="{{ route('payments.transactions') }}">
                                    @lang('profile.payments')
                                </a>
                            </li>
                            <li
                                class="list-group-item {{ active(['user.deleteAccount'], 'active-link', 'user-profile') }}">
                                <a href="{{ route('user.deleteAccount') }}">
                                    @lang('profile.deleteAccount')
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="profile-info">
                        @if ($title || $subTitle)
                            <div class="{{ trim($heroClass . ' hero-title') }}">
                                @if (!empty($title))
                                    <{{ $heading }}>{{ $title }}</{{ $heading }}>
                                @endif
                                @if (!empty($subTitle))
                                    <p>{{ $subTitle ?? '' }}</p>
                                @endif
                            </div>
                        @endif
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </x-page-wrapper>
</x-application-no-widget-wrapper>
