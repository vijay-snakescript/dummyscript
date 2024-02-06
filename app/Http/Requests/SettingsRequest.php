<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "settings.app_name" => 'required',
            "settings.website_email" => 'required|email',
            "settings.app_url" => 'required|url',
            "settings.website_logo" => 'nullable|image|mimes:jpeg,png,jpg,svg,webp',
            "settings.website_logo_dark" => 'nullable|image|mimes:jpeg,png,jpg,svg,webp',
            "settings.website_login_logo" => 'nullable|image|mimes:jpeg,png,jpg,svg,webp',
            "settings.favicon" => 'nullable|image|mimes:png,jpg,gif',
            "settings.meta_title" => 'nullable|string',
            "settings.meta_description" => 'nullable|string|max:255',
            "settings.terms_link" => 'required',
            "settings.privacy_link" => 'required',
            "settings.date_format" => 'required|string',
            "settings.datetime_format" => 'required|string',
            "settings.auth_pages_image" => 'nullable|image|mimes:jpeg,png,jpg,webp',
            "settings.FB_ID" => 'nullable|string',
            "settings.FB_SECRET" => 'nullable|string',
            "settings.header_code" => 'nullable',
            "settings.footer_code" => 'nullable',
            "settings.recaptcha_status" => 'nullable|bool',
            "settings.recaptcha_site" => 'nullable|string',
            "settings.recaptcha_secret" => 'nullable|string',
            "settings.recaptcha_login" => 'nullable|bool',
            "settings.recaptcha_signup" => 'nullable|bool',
            "settings.recaptcha_contact" => 'nullable|bool',
            'settings.recaptcha_on_admin_login' => 'nullable|bool',
            "settings.cooldown_expires_hours" => 'nullable|integer|min:0',
            "settings.default_locale" => 'nullable|string',
            "settings._footer_copyright" => 'nullable|string',
            "settings.public_user_role" => 'required|exists:App\Models\Role,id',
            "settings.activation_required" => 'nullable|bool',
            "settings.activation_max_attempts" => 'required|integer|min:0',
            "settings.activation_time_period" => 'required|integer|min:0',
            "settings.default_user_image" => 'nullable|image|mimes:jpeg,png,jpg,svg,webp,gif',
            "settings.debug" => 'nullable',
            "settings.maintenance_mode" => 'nullable|bool',
            "settings.maintenance_note" => 'nullable|string',
            "settings.maintenance_token" => 'required_if:settings.maintenance_mode,==,1|nullable|min:3',
            'settings.mail_from_name' => 'nullable|string',
            'settings.mail_from_address' => 'nullable|email',
            'settings.mail_use_smtp' => 'nullable|string',
            'settings.mail_smtp_host' => 'nullable|string',
            'settings.mail_smtp_port' => 'nullable|string',
            'settings.mail_smtp_encryption' => 'nullable|in:null,ssl,tls',
            'settings.mail_smtp_username' => 'nullable|string',
            'settings.mail_smtp_password' => 'nullable|string',
            // 'settings.FILESYSTEM_DRIVER' => 'required|in:public,s3,wasabi',
            // 'settings.WAS_ACCESS_KEY_ID' => 'required_if:settings.FILESYSTEM_DRIVER,==,wasabi',
            // 'settings.WAS_SECRET_ACCESS_KEY' => 'required_if:settings.FILESYSTEM_DRIVER,==,wasabi',
            // 'settings.WAS_DEFAULT_REGION' => 'required_if:settings.FILESYSTEM_DRIVER,==,wasabi',
            // 'settings.WAS_BUCKET' => 'required_if:settings.FILESYSTEM_DRIVER,==,wasabi',
            // 'settings.AWS_ACCESS_KEY_ID' => 'required_if:settings.FILESYSTEM_DRIVER,==,s3',
            // 'settings.AWS_SECRET_ACCESS_KEY' => 'required_if:settings.FILESYSTEM_DRIVER,==,s3',
            // 'settings.AWS_DEFAULT_REGION' => 'required_if:settings.FILESYSTEM_DRIVER,==,s3',
            // 'settings.AWS_BUCKET' => 'required_if:settings.FILESYSTEM_DRIVER,==,s3',
        ];
    }

    public function messages()
    {
        return [
            'settings.purchase_code.*' => __('Please register your purchase first.'),
        ];
    }
}
