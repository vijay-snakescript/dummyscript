<x-application-no-widget-wrapper>
    <x-page-wrapper :title="trans('contact.contactUs')" heading="h1">
        <div class="row">
            <div class="col-md-12">
                <form id="frm-contact" method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label" for="email">{{ trans('contact.attributes.email') }}</label>
                        <input type="text" name="email"
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email"
                            value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }} mb-3">
                        <label for="subject" class="form-label">{{ trans('contact.attributes.subject') }}</label>
                        <input type="text" name="subject"
                            class="form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" id="subject"
                            value="{{ old('subject') }}">
                        @if ($errors->has('subject'))
                            <span class="invalid-feedback">
                                {{ $errors->first('subject') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="message" class="form-label">{{ trans('contact.attributes.message') }}</label>
                        <textarea name="message" rows="7" id="message"
                            class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" aria-labelledby="@lang('contact.attributes.message')">{{ old('message') }}</textarea>
                        @if ($errors->has('message'))
                            <span class="invalid-feedback">
                                {{ $errors->first('message') }}
                            </span>
                        @endif
                    </div>
                    @if ($errors->has('captcha'))
                        <div class="form-group mb-3">
                            <div class="is-invalid"></div>
                            <span class="invalid-feedback">
                                {{ $errors->first('captcha') }}
                            </span>
                        </div>
                    @endif
                    @if (setting('recaptcha_status', 0) && setting('recaptcha_contact', 0))
                        <x-primary-button class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site') }}"
                            data-callback="onSubmit">
                            {{ trans('contact.submit') }}
                        </x-primary-button>
                    @else
                        <x-primary-button data-loading>
                            {{ trans('contact.submit') }}
                        </x-primary-button>
                    @endif
                </form>
            </div>
        </div>
        <x-ad-slot />
    </x-page-wrapper>
    @if (setting('recaptcha_status', 0) && setting('recaptcha_contact', 0))
        @push('page_scripts')
            <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}" async defer>
            </script>
            <script type="text/javascript">
                function onSubmit(token) {
                    document.getElementById("frm-contact").submit();
                }
            </script>
        @endpush
    @endif
</x-application-no-widget-wrapper>
