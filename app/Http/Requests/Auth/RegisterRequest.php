<?php

namespace App\Http\Requests\Auth;

use App\Traits\CaptchaTrait;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use CaptchaTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $requireRecaptcha = setting('recaptcha_signup', 0);
        $this->merge([
            'captcha' => $this->captchaCheck($requireRecaptcha),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'captcha'  => 'required|accepted',
        ];
    }

    public function messages()
    {
        return [
            'captcha.accepted'      => trans('auth.captchaWrong')
        ];
    }
}
