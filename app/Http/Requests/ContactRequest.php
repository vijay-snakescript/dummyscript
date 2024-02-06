<?php

namespace App\Http\Requests;

use Setting;
use App\Traits\CaptchaTrait;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    use CaptchaTrait;

    protected $availableAttributes = 'contact.attributes';

    // use CaptchaTrait;

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
        $requireRecaptcha = Setting::get('recaptcha_contact', 0);
        $this->merge([
            'captcha' => $this->captchaCheck($requireRecaptcha),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
            'captcha' => 'required|accepted',
        ];
    }

    public function messages()
    {
        return [
            'captcha.accepted'      => trans('auth.captchaWrong')
        ];
    }
}
