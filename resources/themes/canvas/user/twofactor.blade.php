<x-user-profile :title="empty(Auth::user()->google2fa_secret) ? __('profile.setupAuthApp') : __('profile.2faDescription')" :sub-title="empty(Auth::user()->google2fa_secret) ? __('profile.setupAuthAppDesc') : __('profile.disable2faHelp')">
    @if (empty(Auth::user()->google2fa_secret))
        <div class="row">
            <div class="col-md-6">
                <x-form :route="route('user.twofactor.update')">
                    <div class="row mb-3">
                        <div class="col-md-3 col-sm-12"><label class="form-label fw-bold">@lang('admin.email'):</label></div>
                        <div class="col-md-9 col-sm-12">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 col-sm-12"><label class="form-label fw-bold">@lang('profile.manualKey'):</label></div>
                        <div class="col-md-9 col-sm-12">{{ $secret }}</div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="fw-bold">
                            @lang('profile.scanQrCode')
                        </div>
                        <p class="text-muted">@lang('profile.scanQrCodeHelp')</p>
                        <div class="text-start">
                            @if (Str::of($qr_image)->startsWith('data:image'))
                                <img src="{!! $qr_image !!}" alt="Scan Me">
                            @else
                                {!! $qr_image !!}
                            @endif
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">@lang('profile.verifyCodeFromApp')</label>
                        <x-text-input type="text" name="code" required />
                    </div>
                    <div class="form-group mb-3">
                        <input type="submit" value="@lang('common.continue')" class="btn btn-primary" />
                    </div>
                </x-form>
            </div>
        </div>
    @else
        <div class="form-group mb-3">
            <a class="btn btn-danger" href="{{ route('user.twofactor.disable') }}">@lang('profile.disable2fa')</a>
        </div>
    @endif
</x-user-profile>
