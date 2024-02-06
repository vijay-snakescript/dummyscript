<x-user-profile :title="__('common.editProfile')">
    <x-form :route="route('user.profile.update')" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label class="form-label">@lang('tools.fullName')</label>
            <input class="form-control" type="text" name="name" required value="{{ $user->name }}" />
        </div>
        <div class="form-group mb-3">
            <label class="form-label">@lang('profile.username')</label>
            <input class="form-control" type="text" name="username" required value="{{ $user->username }}" />
        </div>
        <div class="form-group mb-3">
            <label class="form-label">@lang('profile.about')</label>
            <textarea aria-labelledby="{{ __('profile.about') }}" class="form-control" type="text" name="about">{{ $user->about }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">@lang('profile.image')</label>
            <input class="form-control" type="file" name="image" />
        </div>
        <div class="form-group mb-3">
            <input type="submit" value="Save" class="btn btn-primary" />
        </div>
    </x-form>
</x-user-profile>
