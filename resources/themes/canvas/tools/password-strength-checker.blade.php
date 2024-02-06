<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row mt-4">
                <div class="col-md-12 mt-2 mb-3">
                    <div class="password-input">
                        <input class="form-control form-control-lg" name="password" id="password" type="password"
                            placeholder="Password" aria-label="Recipient's username with two button addons">
                        <button class="btn-input-icon show-password" id="togglePassword" type="button"></button>
                    </div>
                </div>
                <div class="col-md-12 mt-2 mb-3">
                    <div class="strength-progress progress" id="password-text"></div>
                </div>
                <div class="col-md-12">
                    <ul class="list-check mb-0">
                        <li class="cross lower-case">@lang('tools.lowercaseLetters')</li>
                        <li class="cross upper-case">@lang('tools.uppercaseLetters')</li>
                        <li class="cross one-number">@lang('tools.number09')</li>
                        <li class="cross one-special-char">@lang('tools.specialCharacter')</li>
                        <li class="cross eight-character">@lang('tools.atleastLimitCharacter')</li>
                    </ul>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    <script>
        const APP = function() {
            let state = false;
            const password = document.getElementById("password");
            const passwordStrengthElement = document.getElementById("password-text");
            const lowerCase = document.querySelector(".lower-case");
            const upperCase = document.querySelector(".upper-case");
            const number = document.querySelector(".one-number");
            const specialChar = document.querySelector(".one-special-char");
            const eightChar = document.querySelector(".eight-character");
            const attachEvents = function() {
                    password.addEventListener("keyup", function() {
                        let pass = document.getElementById("password").value;
                        passwordStrengthTest(pass);
                    });
                    password.addEventListener("input", function() {
                        let pass = document.getElementById("password").value;
                        passwordStrengthTest(pass);
                    });

                    document.getElementById('togglePassword').addEventListener('click', e => {
                        e.target.classList.toggle('show-password')
                        e.target.classList.toggle('hide-password')
                        togglePasswordField()
                    })
                },
                passwordCaseTest = function(password) {
                    let strength = 0;
                    console.log(password.match(/([a-z])/))
                    if (password.match(/([a-z])/)) {
                        strength += 1;
                        lowerCase.classList.remove('cross');
                        lowerCase.classList.add('check');
                    } else {
                        lowerCase.classList.add('cross');
                        lowerCase.classList.remove('check');
                    }

                    if (password.match(/([A-Z])/)) {
                        strength += 1;
                        upperCase.classList.remove('cross');
                        upperCase.classList.add('check');
                    } else {
                        upperCase.classList.add('cross');
                        upperCase.classList.remove('check');
                    }

                    if (password.match(/([0-9])/)) {
                        strength += 1;
                        number.classList.remove('cross');
                        number.classList.add('check');
                    } else {
                        number.classList.add('cross');
                        number.classList.remove('check');
                    }

                    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
                        strength += 1;
                        specialChar.classList.remove('cross');
                        specialChar.classList.add('check');
                    } else {
                        specialChar.classList.add('cross');
                        specialChar.classList.remove('check');
                    }
                    if (password.length > 7) {
                        strength += 1;
                        eightChar.classList.remove('cross');
                        eightChar.classList.add('check');
                    } else {
                        eightChar.classList.add('cross');
                        eightChar.classList.remove('check');
                    }
                },
                togglePasswordField = function() {
                    state ? document.getElementById("password").setAttribute("type", "password") : document
                        .getElementById("password").setAttribute("type", "text");
                    state = !state
                },
                passwordStrengthTest = function(password) {
                    passwordCaseTest(password)
                    if (password.length == 0) {
                        passwordStrengthElement.innerHTML = "";

                        return;
                    }
                    var regex = new Array();
                    regex.push("[A-Z]");
                    regex.push("[a-z]");
                    regex.push("[0-9]");
                    regex.push("[$@$!%*#?&]");
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

                    passwordStrengthElement.innerHTML = strength;
                };

            return {
                init: function() {
                    attachEvents()
                },

            }
        }();

        document.addEventListener("DOMContentLoaded", function(event) {
            APP.init();
        });
    </script>
</x-application-tools-wrapper>
