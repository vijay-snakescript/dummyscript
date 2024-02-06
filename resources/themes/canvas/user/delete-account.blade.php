<x-user-profile :title="__('profile.deleteAccount')">
    <p class="mb-4">
        <i class="fas fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
        {!! __('profile.deleteAccountHelp', ['days' => setting('restore_user_cutoff', 30)]) !!}
        <i class="fas fa-exclamation-triangle fa-fw" aria-hidden="true"></i>
    </p>
    <x-form :route="route('user.deleteAccount.action')">
        @method('DELETE')
        <div class="form-group mb-3">
            <label class="form-label">@lang('admin.password')</label>
            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password"
                placeholder="@lang('admin.enterPassword')" required />
            <x-input-error :messages="$errors->get('password')" />
        </div>
        <div class="form-group mb-3">
            <label for="checkConfirmDelete"
                class="form-label d-block {{ $errors->has('checkConfirmDelete') ? ' is-invalid' : '' }}">
                <input type="checkbox" name="checkConfirmDelete" id="checkConfirmDelete">
                @lang('profile.confirmDeleteAccount')
            </label>
            <x-input-error :messages="$errors->get('checkConfirmDelete')" />
        </div>
        <div class="col-md-12 mb-3">
            <x-button type="submit" class="btn-danger">@lang('profile.deleteMyAccount')</x-button>
        </div>
    </x-form>
</x-user-profile>
