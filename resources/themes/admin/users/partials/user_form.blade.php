<form action="{{ isset($userEdit) ? route('admin.users.update') : route('admin.users.store') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $userEdit->id ?? null }}">
    <div class="form-group mb-3">
        <label for="name" class="form-label">@lang('admin.name')</label>
        <input class="form-control" id="name" name="name" value="{{ $userEdit->name ?? null }}" type="text"
            placeholder="@lang('admin.enterName')" required>
    </div>
    <div class="form-group mb-3">
        <label for="name" class="form-label">@lang('admin.username')</label>
        <input class="form-control" id="username" name="username" value="{{ $userEdit->username ?? null }}"
            type="text" placeholder="@lang('admin.enterUsername')" required>
    </div>
    <div class="form-group mb-3">
        <label for="email" class="form-label">@lang('admin.email')</label>
        <input class="form-control" id="email" name="email" value="{{ $userEdit->email ?? null }}" type="email"
            placeholder="@lang('admin.enterEmail')" required>
    </div>
    <div class="form-group mb-3">
        <label for="password" class="form-label">@lang('admin.password')
            @if (isset($userEdit))
                <span>@lang('admin.passwordDonot')</span>
            @endif
        </label>
        <input class="form-control" id="password" name="password" type="text" placeholder="@lang('admin.enterPassword')"
            @if (!isset($userEdit)) required @endif>
    </div>
    <div class="form-group mb-3">
        <label for="about" class="form-label">@lang('admin.about')</label>
        <input class="form-control" id="about" name="about" value="{{ $userEdit->about ?? null }}" type="text"
            placeholder="@lang('admin.enterAbout')" required>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h6>@lang('admin.roles')</h6>
        </div>
        @foreach ($roles as $role)
            <div class="col-md-4">
                <div class="form-check mb-3">
                    <input class="form-check-input permission-checkbox" id="role" type="checkbox"
                        name="roles[{{ $role->id }}]" value="{{ $role->id }}"
                        @if (isset($userEdit) && $userEdit->hasRole($role->id)) checked @endif>
                    <label class="form-check-label" for="role">{{ $role->name }}</label>
                </div>
            </div>
        @endforeach
    </div>
    <div class="card-footer text-end">
        <button type="submit" class="btn btn-primary">@lang('common.save')</button>
    </div>
</form>
