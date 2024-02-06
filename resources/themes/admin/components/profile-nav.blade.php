@props([
    'account' => "",
    'password' => "",
    'notification' => "",
    'factor' => "",
    'user',
])

<div class="tab-content rounded-bottom">
    <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-601">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item"><a class="nav-link {{$account}}" aria-current="page" href="{{ route('admin.profile')}}"><i class="lni lni-user"></i>
                    @lang('admin.account')</a></li>
            <li class="nav-item"><a class="nav-link {{$password}}" href="{{ route('admin.password')}}"><i class="lni lni-lock-alt"></i> @lang('common.changePassword')</a>
            </li>
            {{-- <li class="nav-item"><a class="nav-link {{$notification}}" href="#"><i class="lni lni-popup"></i> Notifications</a></li> --}}
            <li class="nav-item"><a class="nav-link {{$factor}}" href="{{ route('admin.mfa')}}"><i class="lni lni-protection"></i> @lang('profile.2faDescription')</a></li>
        </ul>
    </div>
</div>
