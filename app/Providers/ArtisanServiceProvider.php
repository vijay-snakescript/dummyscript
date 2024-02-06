<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use App\Helpers\Facads\Gateway;
use App\Components\ToolsManager;
use App\Components\GatewayFactory;
use App\Components\PaymentManager;
use Illuminate\Support\Collection;
use App\Components\Gateways\PayPal;
use App\Components\Gateways\PayStack;
use App\Components\Gateways\BankTransfer;
use App\Components\Gateways\Stripe;
use App\Components\Gateways\Mollie;
use App\Components\Gateways\RazorPay;
use App\Components\Gateways\Skrill;
use App\Helpers\Classes\MenuManager;
use App\Helpers\Classes\SEOAnalyzer;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Classes\Menu\RegisterMenu;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacads();
        $this->initMacros();

        if ($this->app->isLocal()) {
            // $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->validation();
        Vite::useScriptTagAttributes([
            'defer' => true, // Specify an attribute without a value...
        ]);
    }

    protected function validation()
    {
        Validator::extend('fqdn', function ($attribute, $value, $parameters, $validator) {
            $validator->setCustomMessages(['fqdn' => 'The :attribute format is invalid']);

            return preg_match("/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/", $value);
            // return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value);
            // return preg_match('/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i', $value);
        });

        Validator::extend('max_words', function ($field, $value, $parameters, $validator) {
            $words = preg_split('@\s+@i', $value);
            if (count($words) <= $parameters[0]) {
                return true;
            }

            return false;
        });
        Validator::replacer('max_words', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':max', $parameters[0], $message);
        });
    }

    protected function registerFacads()
    {
        $this->app->singleton('menumanager', function () {
            return new MenuManager();
        });

        $this->app->singleton('registermenu', function () {
            return new RegisterMenu();
        });

        $this->app->singleton(UniqueSlug::class, function () {
            return new \App\Helpers\Classes\UniqueSlug;
        });

        $this->app->singleton('ToolsManager', function ($app) {
            return new ToolsManager($app);
        });

        $this->app->singleton('gateway', function ($app) {
            return new GatewayFactory($app);
        });
        $this->registerGateways();

        $this->app->singleton('payment', function ($app) {
            $defaults = $app['config']->get('artisan.gateway_defaults', array());

            return new PaymentManager($app, $defaults);
        });

        $this->app->singleton('seo-analyzer', SEOAnalyzer::class);
    }

    public function registerGateways()
    {
        Gateway::register('paypal', PayPal::class);
        Gateway::register('stripe', Stripe::class);
        Gateway::register('mollie', Mollie::class);
        Gateway::register('paystack', PayStack::class);
        Gateway::register('razorpay', RazorPay::class);
        Gateway::register('skrill', Skrill::class);
        Gateway::register('banktransfer', BankTransfer::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ToolsManager'];
    }

    protected function initMacros()
    {
        /**
         * Similar to pluck, with the exception that it can 'pluck' more than one column.
         * This method can be used on either Eloquent models or arrays.
         * @param string|array $cols Set the columns to be selected.
         * @return Collection A new collection consisting of only the specified columns.
         */
        Collection::macro('pick', function ($cols = ['*']) {
            $cols = is_array($cols) ? $cols : func_get_args();
            $obj = clone $this;

            // Just return the entire collection if the asterisk is found.
            if (in_array('*', $cols)) {
                return $this;
            }

            return $obj->transform(function ($value) use ($cols) {
                $ret = [];
                foreach ($cols as $col) {
                    // This will enable us to treat the column as a if it is a
                    // database query in order to rename our column.
                    $name = $col;
                    if (preg_match('/(.*) as (.*)/i', $col, $matches)) {
                        $col = $matches[1];
                        $name = $matches[2];
                    }

                    // If we use the asterisk then it will assign that as a key,
                    // but that is almost certainly **not** what the user
                    // intends to do.
                    $name = str_replace('.*.', '.', $name);

                    // We do it this way so that we can utilise the dot notation
                    // to set and get the data.
                    Arr::set($ret, $name, data_get($value, $col));
                }

                return $ret;
            });
        });
    }
}
