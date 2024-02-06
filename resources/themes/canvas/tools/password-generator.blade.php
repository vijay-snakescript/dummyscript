<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="password-generator">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <div class="flex-wrap d-flex mb-4">
                                <label class="custom-radio">
                                    <input class="options-radio" id="say" type="radio" name="options"
                                        value="say">
                                    <span></span>@lang('tools.sayText')
                                </label>
                                <label class="custom-radio">
                                    <input class="options-radio" id="read" type="radio" name="options"
                                        value="read" checked />
                                    <span></span>@lang('tools.readText')
                                </label>
                                <label class="custom-radio">
                                    <input class="options-radio" id="all" type="radio" name="options"
                                        value="all" checked />
                                    <span></span>@lang('tools.allText')
                                </label>
                            </div>
                            <div class="flex-wrap d-flex mb-4">
                                <label class="custom-checkbox">
                                    <x-text-input class="custom-checkbox-input regenerate-change-event" id="upper"
                                        type="checkbox" checked />
                                    <span></span>@lang('tools.uppercase')
                                </label>
                                <label class="custom-checkbox">
                                    <x-text-input class="custom-checkbox-input regenerate-change-event" id="lower"
                                        type="checkbox" checked />
                                    <span></span>@lang('tools.lowercase')
                                </label>
                                <label class="custom-checkbox">
                                    <x-text-input class="custom-checkbox-input regenerate-change-event" id="number"
                                        type="checkbox" checked />
                                    <span></span>@lang('tools.numbers')
                                </label>
                                <label class="custom-checkbox">
                                    <x-text-input class="custom-checkbox-input regenerate-change-event" id="special"
                                        type="checkbox" checked />
                                    <span></span>@lang('tools.specialCharacter')
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group mb-3">
                            <x-text-input class="form-control-lg password-strength" name="password" id="password"
                                type="text" placeholder="Password" />
                            <button type="button" class="btn btn-outline-primary copy-clipboard"
                                data-clipboard-target="#password" data-copied="{{ __('common.copied') }}"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ __('common.copyToClipboard') }}">
                                <i class="an an-copy-to-clipboard"></i>
                            </button>
                            <button class="btn btn-outline-primary regenerate-event" data-bs-toggle="tooltip"
                                title="@lang('tools.regeneratePassword')" type="button" id="button">
                                <i class="an an-reload"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="strength-prograss progress" id="password-text"></div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="range-slider">
                            <input class="range-slider__range regenerate-change-event" type="range" value="14"
                                min="3" max="50" id="password-strength">
                            <span class="range-slider__value"></span>
                        </div>
                    </div>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const numberChars = "0123456789";
                const upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                const lowerChars = "abcdefghiklmnopqrstuvwxyz";
                const specialChars = "$@$!%*#?&";
                const shuffleArray = function(array) {
                        for (var i = array.length - 1; i > 0; i--) {
                            var j = Math.floor(Math.random() * (i + 1));
                            var temp = array[i];
                            array[i] = array[j];
                            array[j] = temp;
                        }
                        return array;
                    },
                    attachEvents = function() {
                        document.querySelectorAll('.options-radio').forEach(input => {
                            input.addEventListener('change', e => {
                                updateCheckboxes();
                            })
                        });

                        document.querySelectorAll('.regenerate-change-event').forEach(input => {
                            input.addEventListener('change', e => {
                                generatePassword();
                            })
                        });
                        document.querySelectorAll('.regenerate-event').forEach(input => {
                            input.addEventListener('click', e => {
                                generatePassword();
                            })
                        });
                    },
                    updateCheckboxes = function() {
                        checkedItem = document.querySelectorAll('.options-radio:checked')[0]
                        if (checkedItem.checked === true) {
                            if (checkedItem.value == "all") {
                                document.getElementById('upper').checked = true;
                                document.getElementById('lower').checked = true;
                                document.getElementById('special').checked = true;
                                document.getElementById('number').checked = true;
                                document.getElementById('special').disabled = false;
                                document.getElementById('number').disabled = false;
                            }
                            if (checkedItem.value == "read") {
                                document.getElementById('upper').checked = true;
                                document.getElementById('lower').checked = true;
                                document.getElementById('special').checked = false;
                                document.getElementById('number').checked = false;
                                document.getElementById('special').disabled = false;
                                document.getElementById('number').disabled = false;

                            }
                            if (checkedItem.value == "say") {
                                document.getElementById('upper').checked = true;
                                document.getElementById('lower').checked = true;
                                document.getElementById('special').checked = false;
                                document.getElementById('number').checked = false;
                                document.getElementById('special').disabled = true;
                                document.getElementById('number').disabled = true;
                            }
                            generatePassword();
                        }
                    },
                    generatePassword = function() {
                        const passwordLength = parseInt(document.getElementById("password-strength").value);
                        var count = 0;
                        var allChars = "";
                        var randPasswordArray = Array(passwordLength);
                        if (document.getElementById('upper').checked) {
                            allChars = allChars + upperChars;
                            count++;
                            randPasswordArray.push(upperChars);
                        }
                        if (document.getElementById('lower').checked) {
                            allChars = allChars + lowerChars;
                            count++;
                            randPasswordArray.push(lowerChars);
                        }
                        if (document.getElementById('number').checked) {
                            allChars = allChars + numberChars;
                            count++;
                            randPasswordArray.push(numberChars);
                        }
                        if (document.getElementById('special').checked) {
                            allChars = allChars + specialChars;
                            count++;
                            randPasswordArray.push(specialChars);
                        }

                        if (count == 0 || passwordLength < 3) {
                            ArtisanApp.toastError('{{ __('tools.minimunPasswordLength') }}');

                            return;
                        }
                        randPasswordArray = randPasswordArray.fill(allChars, count);
                        const password = shuffleArray(randPasswordArray.map(function(x) {
                            return x[Math.floor(Math.random() * x.length)]
                        })).join('');

                        document.getElementById("password").value = password;
                        passwordStrength(password)
                    },
                    passwordStrength = function(password) {
                        var password_strength = document.getElementById("password-text");
                        if (password.length == 0) {
                            password_strength.innerHTML = "";
                            return;
                        }
                        var regex = new Array();
                        regex.push("[" + upperChars + "]");
                        regex.push("[" + lowerChars + "]");
                        regex.push("[" + numberChars + "]");
                        regex.push("[" + specialChars + "]");
                        var passed = 0;
                        for (var i = 0; i < regex.length; i++) {
                            if (new RegExp(regex[i]).test(password)) {
                                passed++;
                            }
                        }
                        if (password.length >= 14) {
                            passed++
                        }

                        var strength = "";
                        switch (passed) {
                            case 0:
                            case 1:
                            case 2:
                                strength =
                                    '<small class="progress-bar bg-danger" style="width: 33%" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">{{ __('tools.weak') }}</small>';
                                break;
                            case 3:
                                strength =
                                    '<small class="progress-bar bg-warning" style="width: 60%" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">{{ __('tools.medium') }}</small>';
                                break;
                            case 4:
                            case 5:
                                strength =
                                    '<small class="progress-bar bg-success" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">{{ __('tools.strong') }}</small>';
                                break;
                        }

                        password_strength.innerHTML = strength;
                    };

                return {
                    init: function() {
                        attachEvents()
                        updateCheckboxes();
                    }
                };
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
