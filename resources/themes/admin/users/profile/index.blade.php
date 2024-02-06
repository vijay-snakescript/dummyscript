<x-app-layout>
    <x-profile-nav :account="'active'" :user='$user'>
    </x-profile-nav>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('admin.profile.update') }}" method="POST">
                                <div class="card">
                                    <div class="card-header"><strong>@lang('admin.account')</strong></div>
                                    <div class="card-body">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $user->id ?? null }}">
                                        <div class="form-group mb-3">
                                            <label for="name"
                                                class="text-md-right text-md-right">@lang('admin.name')</label>
                                            <input class="form-control" id="name" name="name"
                                                value="{{ $user->name ?? null }}" type="text"
                                                placeholder="@lang('admin.enterName')" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="name"
                                                class="text-md-right text-md-right">@lang('admin.username')</label>
                                            <input class="form-control" id="username" name="username"
                                                value="{{ $user->username ?? null }}" type="text"
                                                placeholder="@lang('admin.enterUsername')" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email"
                                                class="text-md-right text-md-right">@lang('admin.email')</label>
                                            <input class="form-control" id="email" name="email"
                                                value="{{ $user->email ?? null }}" type="email"
                                                placeholder="@lang('admin.enterEmail')" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="about"
                                                class="text-md-right text-md-right">@lang('admin.about')</label>
                                            <input class="form-control" id="about" name="about"
                                                value="{{ $user->about ?? null }}" type="text"
                                                placeholder="@lang('admin.enterAbout')" required>
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
        </div>
    </div>
</x-app-layout>
