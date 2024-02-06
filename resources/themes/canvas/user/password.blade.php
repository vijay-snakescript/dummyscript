<x-user-profile :title="__('common.changePassword')">
    <x-form :route="route('user.password.update')">
        <div class="form-group mb-3">
            <label class="form-label">@lang('profile.currentPassword')</label>
            <input class="form-control" type="password" name="current_password" placeholder="@lang('profile.enterCurrentPassword')" required />
        </div>
        <div class="form-group mb-3">
            <label class="form-label">@lang('admin.password')</label>
            <input class="form-control" type="password" name="new_password" placeholder="@lang('profile.enterNewPassword')" required />
        </div>
        <div class="form-group mb-3">
            <label class="form-label">@lang('profile.confirmPassword')</label>
            <input class="form-control" type="password" name="password_confirmation" placeholder="@lang('profile.confirmNewPassword')"
                required />
        </div>
        <div class="form-group mb-3">
            <input type="submit" value="Update" class="btn btn-primary" />
        </div>
    </x-form>
</x-user-profile>
