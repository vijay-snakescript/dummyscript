<x-app-layout>
    <x-profile-nav :password="'active'" :user='$user'>
    </x-profile-nav>
    <div class="card mb-3">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        <div class="card">
                            <div class="card-header"><strong>@lang('admin.password')</strong></div>
                            <div class="card-body">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id ?? null }}">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">@lang('admin.password')</label>
                                    <input class="form-control" id="password" name="password" type="text"
                                        placeholder="@lang('admin.enterPassword')" required>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">@lang('common.save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
