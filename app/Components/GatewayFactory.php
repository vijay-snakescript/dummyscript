<?php

namespace App\Components;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Contracts\GatewayInterface;

class GatewayFactory
{
    /**
     * Collection of Widget
     * @var object \Illuminate\Support\Collection
     */
    protected $collection;

    /**
     * Construct for the Widget manager
     * @return void
     */
    public function __construct()
    {
        $this->collection = Collection::make([]);
    }

    /**
     * Add Widget Class to a collection
     *
     * @param string $key
     *
     * @param Gateway $gateway
     *
     * @return Gateway $gateway
     */
    public function register($key, $gateway)
    {
        if (is_subclass_of($gateway, GatewayInterface::class, true)) {
            if (!$this->find($key)) {
                $this->collection->put($key, $gateway);
            }

            return $gateway;
        }

        throw new Exception("Class '$gateway' doesn't implement GatewayInterface.");
    }

    /**
     * Create gateway instance.
     *
     * @param string $key
     * @param array $config
     * @param Request $request
     *
     * @return Payment
     */
    public function create(string $key, Request $request, array $config = [])
    {
        if ($gateway = $this->get($key)) {
            return new $gateway($request, $config);
        }

        throw new Exception("Gateway class '{$key}' not registered.");
    }

    /**
     * Get the Widget from collection by given key
     * @param string $key
     * @return Gateway $widget
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * Get the Widget from collection by given key
     *
     * @param string $gateway
     *
     * @return Bool
     */
    public function find($gateway)
    {
        return $this->collection->contains($gateway);
    }

    /**
     * Returns All the widget in collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->collection;
    }
}
