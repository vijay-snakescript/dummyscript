<?php

namespace App\Components;

use Exception;
use App\Helpers\Facads\Gateway;
use Illuminate\Support\Manager;

class PaymentManager extends Manager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The registered gateways
     */
    protected $gateways;

    /**
     * The default settings, applied to every gateway
     */
    protected $defaults;

    /**
     * Create a new Gateway manager instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @param  GatewayFactory $factory
     * @param  array
     */
    public function __construct($app, $defaults = array())
    {
        $this->app = $app;
        $this->defaults = $defaults;

        $this->resolve();
    }

    public function resolve()
    {
        $gateways = Gateway::all();
        $gateways->each(function ($class, $key) {
            $config = $this->getConfig($key);
            $gateway = Gateway::create($key, $this->app['request'], $config);
            if ($gateway->isActive() && $gateway->isConfigured()) {
                try {
                    $gateway->initialize();
                    $this->gateways[$key] = $gateway;
                } catch (Exception $e) {
                    dd($e);
                }
            }
        });
    }

    public function all()
    {
        return $this->gateways;
    }

    /**
     * Get a gateway
     *
     * @param  string  The gateway to retrieve (null=default)
     * @return \Omnipay\Common\GatewayInterface
     */
    public function gateway($class = null)
    {
        $class = $class ?: $this->getDefaultDriver();
        if (!isset($this->gateways[$class])) {
            $gateway = Gateway::create($class, $this->app['request']);
            if ($gateway->isActive()) {
                $gateway->initialize($this->getConfig($class));

                $this->gateways[$class] = $gateway;
            }
        }

        return $this->gateways[$class];
    }

    /**
     * Get the configuration, based on the config and the defaults.
     */
    protected function getConfig($name)
    {
        return array_merge(
            $this->defaults,
            $this->app['config']->get('services.gateways.' . $name, [])
        );
    }

    /**
     * Get the default gateway name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['services.gateway'];
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->gateway(), $method], $parameters);
    }
}
