<?php

namespace App\Http\Controllers\Admin;

use Setting;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Role;
use App\Models\WidgetArea;
use Akaunting\Money\Currency;
use App\Models\Advertisement;
use App\Helpers\Classes\DynamicCss;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Http\Requests\SettingsRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;


class SettingsController extends Controller
{
    protected $fields;
    protected $defaults;
    protected $uploads;
    protected $themes;
    protected $checkbox;
    protected $env;
    protected $env_checkbox;

    public function __construct()
    {
        $this->fields = [
            'app_name',
            'website_email',
            'website_contact_number',
            'app_url',
            'meta_title',
            'meta_description',
            'twitter_username',
            'terms_link',
            'privacy_link',
            'date_format',
            'joined_date_format',
            'datetime_format',
            'default_locale',
            'FB_ID',
            'FB_SECRET',
            'FB_REDIRECT',
            'header_code',
            'footer_code',
            'recaptcha_site',
            'recaptcha_secret',
            'cooldown_expires_hours',
            '_header_menu',
            '_footer_menu',
            '_footer_social',
            '_sidebar_menu',
            'admin_pagination',
            'front_pagination',
            'public_user_role',
            'activation_max_attempts',
            'activation_time_period',
            'maintenance_note',
            'maintenance_allowed_ips',
            'maintenance_token',
            'mail_from_name',
            'mail_from_address',
            'mail_smtp_host',
            'mail_smtp_port',
            'mail_smtp_encryption',
            'mail_smtp_username',
            'mail_smtp_password',
            'SYSTEM_TIMEZONE',
            'footer_widgets',
            'footer_widget_columns',
            'footer_copyright_bar',
            'footer_center_copyright',
            '_footer_copyright',
            'FILESYSTEM_DRIVER' => 'FILESYSTEM_DRIVER',
            'WAS_ACCESS_KEY_ID' => 'WAS_ACCESS_KEY_ID',
            'WAS_SECRET_ACCESS_KEY' => 'WAS_SECRET_ACCESS_KEY',
            'WAS_DEFAULT_REGION' => 'WAS_DEFAULT_REGION',
            'WAS_BUCKET' => 'WAS_BUCKET',
            'AWS_ACCESS_KEY_ID' => 'AWS_ACCESS_KEY_ID',
            'AWS_SECRET_ACCESS_KEY' => 'AWS_SECRET_ACCESS_KEY',
            'AWS_DEFAULT_REGION' => 'AWS_DEFAULT_REGION',
            'AWS_BUCKET' => 'AWS_BUCKET',
            'GOOGLE_ID',
            'GOOGLE_SECRET',
            'GOOGLE_REDIRECT',
            'STRIPE_KEY' => 'STRIPE_KEY',
            'STRIPE_SECRET' => 'STRIPE_SECRET',
            'PAYPAL_MODE' => 'PAYPAL_MODE',
            'PAYPAL_SANDBOX_CLIENT_ID' => 'PAYPAL_SANDBOX_CLIENT_ID',
            'PAYPAL_SANDBOX_CLIENT_SECRET' => 'PAYPAL_SANDBOX_CLIENT_SECRET',
            'PAYPAL_LIVE_CLIENT_ID' => 'PAYPAL_LIVE_CLIENT_ID',
            'PAYPAL_LIVE_CLIENT_SECRET' => 'PAYPAL_LIVE_CLIENT_SECRET',
            'PAYPAL_LIVE_APP_ID' => 'PAYPAL_LIVE_APP_ID',
            'PAYPAL_VALIDATE_SSL' => 'PAYPAL_VALIDATE_SSL',
            'PAYPAL_NOTIFY_URL' => 'PAYPAL_NOTIFY_URL',
            'currency',
            'above-tool',
            'above-form',
            'below-form',
            'above-result',
            'below-result',
            'post-above',
            'post-below',
            'admin_pagination',
            'google_webmaster',
            'yandex_webmaster',
            'bing_webmaster',
            'pinterest_webmaster',
            'alexa_webmaster',
            'google_analytics_id',
            'ads_removal_price_monthly',
            'ads_removal_price_yearly',
            'restore_user_cutoff',
            'user_restore_key',
            'restore_user_enc_type',
            'SUPER_ADMIN_ROLE',
            'cookies_consent',
            '_main_menu',
            'RAZORPAY_SECRET',
            'RAZORPAY_KEY',
            'skrill_merchant_email',
            'bank_transfer_details',
            'MOLLIE_KEY',
            'PAYSTACK_PUBLIC_KEY',
            'PAYSTACK_SECRET_KEY',
            'PAYSTACK_PAYMENT_URL',
            'MERCHANT_EMAIL',
        ];

        $this->defaults = [
            '_footer_copyright' => '',
            '_header_menu' => '',
            '_footer_menu' => '',
            '_footer_social' => '',
            '_sidebar_menu' => '',
            'header_code' => '',
            'footer_code' => '',
            'recaptcha_site' => '',
            'recaptcha_secret' => '',
        ];

        $this->uploads = [
            'website_logo' => 'uploads',
            'website_logo_dark' => 'uploads',
            'website_login_logo' => 'uploads',
            'favicon' => 'uploads',
            'og_image' => 'uploads',
            'auth_pages_image' => 'uploads',
            'default_user_image' => 'avatars',
        ];

        $this->themes = [
            'canvas',
        ];

        $this->checkbox = [
            'recaptcha_status' => '0',
            'recaptcha_login' => '0',
            'recaptcha_signup' => '0',
            'recaptcha_contact' => '0',
            'activation_required' => '0',
            'debug' => 'false',
            'maintenance_mode' => '0',
            'enable_header_code' => '0',
            'enable_footer_code' => '0',
            'mail_use_smtp' => 'sendmail',
            'recaptcha_on_admin_login' => '0',
            'maintenance_mode' => '0',
            'footer_widgets' => '0',
            'footer_center_copyright' => '0',
            'footer_copyright_bar' => '0',
            'PAYPAL_ALLOW' => '0',
            'STRIPE_ALLOW' => '0',
            'page_views' => 0,
            'tags_views' => 0,
            'tool_views' => 0,
            'post_views' => 0,
            'append_sitename' => 0,
            'display_faq_homepage' => 0,
            'display_plan_homepage' => 0,
            'display_socialshare_icon' => 0,
            'razor_allow' => 0,
            'skrill_allow' => 0,
            'bank_transfer_allow' => 0,
            'paystack_allow' => 0,
            'mollie_allow' => 0,
            'paystack_allow' => 0,
            'homepage_favorite_tools' => 0,
            'disable_favorite_tools' => 0,
            'disable_auth' => 0,
            'unlimited_usage' => 0,
        ];

        $this->env = [
            'app_url' => 'APP_URL',
            'app_name' => 'APP_NAME',
            'mail_from_name' => 'MAIL_FROM_NAME',
            'mail_from_address' => 'MAIL_FROM_ADDRESS',
            'mail_smtp_host' => 'MAIL_HOST',
            'mail_smtp_port' => 'MAIL_PORT',
            'mail_smtp_encryption' => 'MAIL_ENCRYPTION',
            'mail_smtp_username' => 'MAIL_USERNAME',
            'mail_smtp_password' => 'MAIL_PASSWORD',
            'SYSTEM_TIMEZONE' => 'SYSTEM_TIMEZONE',
            'FILESYSTEM_DRIVER' => 'FILESYSTEM_DRIVER',
            'WAS_ACCESS_KEY_ID' => 'WAS_ACCESS_KEY_ID',
            'WAS_SECRET_ACCESS_KEY' => 'WAS_SECRET_ACCESS_KEY',
            'WAS_DEFAULT_REGION' => 'WAS_DEFAULT_REGION',
            'WAS_BUCKET' => 'WAS_BUCKET',
            'AWS_ACCESS_KEY_ID' => 'AWS_ACCESS_KEY_ID',
            'AWS_SECRET_ACCESS_KEY' => 'AWS_SECRET_ACCESS_KEY',
            'AWS_DEFAULT_REGION' => 'AWS_DEFAULT_REGION',
            'AWS_BUCKET' => 'AWS_BUCKET',
            'STRIPE_KEY' => 'STRIPE_KEY',
            'STRIPE_SECRET' => 'STRIPE_SECRET',
            'PAYPAL_MODE' => 'PAYPAL_MODE',
            'PAYPAL_SANDBOX_CLIENT_ID' => 'PAYPAL_SANDBOX_CLIENT_ID',
            'PAYPAL_SANDBOX_CLIENT_SECRET' => 'PAYPAL_SANDBOX_CLIENT_SECRET',
            'PAYPAL_LIVE_CLIENT_ID' => 'PAYPAL_LIVE_CLIENT_ID',
            'PAYPAL_LIVE_CLIENT_SECRET' => 'PAYPAL_LIVE_CLIENT_SECRET',
            'PAYPAL_LIVE_APP_ID' => 'PAYPAL_LIVE_APP_ID',
            'PAYPAL_VALIDATE_SSL' => 'PAYPAL_VALIDATE_SSL',
            'PAYPAL_NOTIFY_URL' => 'PAYPAL_NOTIFY_URL',
            'FB_ID' => 'FACEBOOK_CLIENT_ID',
            'FB_SECRET' => 'FACEBOOK_CLIENT_SECRET',
            'FB_REDIRECT' => 'FACEBOOK_URL',
            'GOOGLE_ID' => 'GOOGLE_CLIENT_ID',
            'GOOGLE_SECRET' => 'GOOGLE_CLIENT_SECRET',
            'GOOGLE_REDIRECT' => 'GOOGLE_URL',
            'SUPER_ADMIN_ROLE' => 'SUPER_ADMIN_ROLE',
            'RAZORPAY_KEY' => 'RAZORPAY_KEY',
            'RAZORPAY_SECRET' => 'RAZORPAY_SECRET',
            'skrill_merchant_email' => 'skrill_merchant_email',
            'MOLLIE_KEY' => 'MOLLIE_KEY',
            'PAYSTACK_PUBLIC_KEY' => 'PAYSTACK_PUBLIC_KEY',
            'PAYSTACK_SECRET_KEY' => 'PAYSTACK_SECRET_KEY',
            'PAYSTACK_PAYMENT_URL' => 'PAYSTACK_PAYMENT_URL',
            'MERCHANT_EMAIL' => 'MERCHANT_EMAIL',
        ];

        $this->env_checkbox = [
            'debug' => ['key' => 'APP_DEBUG', 'value' => 'false'],
            'cookies_consent' => ['key' => 'COOKIE_CONSENT_ENABLED', 'value' => 'false'],
            'mail_use_smtp' => ['key' => 'MAIL_MAILER', 'value' => 'sendmail'],
        ];
    }

    /**
     * Settings form display
     *
     * @return view
     */
    public function index()
    {
        $theme = Config::get('artisan.front_theme');
        $theme_file = View::exists('themes.' . $theme) ? 'themes.' . $theme : false;
        $themeOptions = json_decode(Setting::get($theme, ''));
        $menus = Menu::get();
        $pages = Page::published()->with('translations')->get();
        $roles = $this->roles();
        $advertisements = Advertisement::active()->get();
        $fonts = google_fonts_list();
        $currencies = Currency::getCurrencies();

        return view('settings.index', compact('themeOptions', 'menus', 'pages', 'roles', 'theme_file', 'advertisements', 'fonts', 'currencies'));
    }

    /**
     * Store settings in database.
     *
     * @param  \App\Requests\Http\SettingsRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SettingsRequest $request, DynamicCss $dynamicCss)
    {
        $settings = $request->input('settings');
        $this->handleEnvSettings($settings);
        $this->storeCheckbox($settings);
        $this->storeFiles($request);
        $this->storeAllOther($settings);
        $this->setFooterWidgets($request);
        $this->storeThemeSettings($settings);
        $this->handleMaintenanceMode($request);
        Setting::save();

        $dynamicCss->build();

        return redirect()
            ->route('admin.settings')
            ->withSuccess(__('settings.savedSuccessfully'));
    }

    protected function setFooterWidgets($request)
    {
        $widgets = (int) $request->input('settings.footer_widget_columns', 4);
        $deleteWidgets = $restoreWidgets = [];
        for ($i = 1; $i <= 6; $i++) {
            if ($i > $widgets) {
                $deleteWidgets[] = "footer-{$i}";
            } else {
                $restoreWidgets[] = "footer-{$i}";
            }
        }

        WidgetArea::whereIn('name', $restoreWidgets)->restore();
        WidgetArea::whereIn('name', $deleteWidgets)->delete();
    }

    /**
     * Store Settings in ENV file.
     *
     * @PermissionAnnotation(name="ignore")
     *
     * @param $settings
     */
    protected function handleEnvSettings($settings)
    {
        $env = DotenvEditor::load();
        $env_fields = $this->env;
        foreach ($env_fields as $key => $value) {
            $setting = isset($settings[$key]) ? $settings[$key] : false;
            if ($setting) {
                $env->setKey($value, $setting);
            }
        }

        $checkboxes = $this->env_checkbox;
        foreach ($checkboxes as $key => $value) {
            if (!is_array($value) && (!isset($value['value']) || $value['key'])) {
                continue;
            }
            $setting = isset($settings[$key]) ? $settings[$key] : $value['value'];
            $env->setKey($value['key'], $setting);
        }

        $env->save();
    }
    /**
     * Store files if present and set settings.
     *
     * @PermissionAnnotation(name="ignore")
     *
     * @param $settings
     */
    protected function storeFiles($request)
    {
        $uploads = $this->uploads;

        foreach ($uploads as $key => $path) {
            $post_key = 'settings.' . $key;
            $del_key = 'settings.delete_' . $key;
            $file = $request->file($post_key);
            if ($file) {
                $ext = $file->getClientOriginalExtension();

                if ($ext === 'svg') {
                    $filename = fileUpload($file, 'uploads');
                } elseif ($path === 'videos') {
                    $filename = fileUpload($file, 'uploads');
                } elseif ($path !== 'videos') {
                    $filename = fileUpload($file, 'uploads');
                }

                $value = $filename;

                Setting::set($key, $value);
            } else if ($request->input($del_key, false)) {
                Setting::set($key, false);
            }
        }
    }

    /**
     * Store theme options and set settings.
     *
     * @PermissionAnnotation(name="ignore")
     *
     * @param $settings
     */
    protected function storeThemeSettings($settings)
    {
        $themes = $this->themes;

        foreach ($themes as $key) {
            $value = isset($settings[$key]) ? json_encode($settings[$key]) : false;
            if ($value) {
                Setting::set($key, $value);
            }
        }
    }

    /**
     * Store all settings and set settings.
     *
     * @PermissionAnnotation(name="ignore")
     *
     * @param $settings
     */
    protected function storeAllOther($settings)
    {
        $fields = $this->fields;
        $defaults = $this->defaults;

        foreach ($fields as $key) {
            $value = isset($settings[$key]) ? $settings[$key] : (isset($defaults[$key]) ? $defaults[$key] : false);
            if ($value !== false) {
                Setting::set($key, $value);
            }
        }

        Setting::save();
    }

    /**
     * Store checkboxes and set settings.
     *
     * @PermissionAnnotation(name="ignore")
     *
     * @param $settings
     */
    protected function storeCheckbox($settings)
    {
        $checkboxes = $this->checkbox;

        foreach ($checkboxes as $key => $default) {
            $value = isset($settings[$key]) ? $settings[$key] : $default;
            Setting::set($key, $value);
        }
    }

    /**
     * Private function to handle maintenance mode
     *
     * @PermissionAnnotation(name="ignore")
     *
     * @param $request
     */
    private function handleMaintenanceMode($request)
    {
        if ($request->input('settings.maintenance_mode', false)) {
            Artisan::call(
                'down',
                [
                    '--secret' => $request->input('settings.maintenance_token'),
                ]
            );
        } elseif (app()->isDownForMaintenance() && !$request->input('settings.maintenance_mode', false)) {
            Artisan::call('up');
        }
    }

    public function roles()
    {
        $list = Role::orderBy('id')->paginate(setting('admin_pagination', 10));

        $list->transform(
            function ($item) {
                $item->is_super = $this->ifSuperRole($item);

                return $item;
            }
        );

        return $list;
    }

    public function ifSuperRole($role)
    {
        return in_array($role->name, (array) setting('super_role'));
    }
}
