<x-app-layout>
    <x-profile-nav :factor="'active'" :user='$user' />
    <div class="row">
        <div class="col-12">
            @if (empty(Auth::user()->google2fa_secret))
                <form action="{{ route('admin.twofactor.update') }}" method="post">
                    <div class="card">
                        @csrf
                        <div class="card-header">
                            <h6 class="mb-0">@lang('profile.2faDescription')</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3 col-sm-12"><label
                                        class="form-label fw-bold">@lang('admin.email'):</label></div>
                                <div class="col-md-9 col-sm-12">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 col-sm-12"><label
                                        class="form-label fw-bold">@lang('profile.manualKey'):</label>
                                </div>
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

                        </div>
                        <div class="card-footer text-end">
                            <input type="submit" value="@lang('common.continue')" class="btn btn-primary" />
                        </div>
                    </div>
                </form>
            @else
                <div class="form-group mb-3">
                    <a class="btn btn-danger" href="{{ route('admin.twofactor.disable') }}">@lang('profile.disable2fa')</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
