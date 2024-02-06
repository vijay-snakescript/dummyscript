<?php

namespace App\Contracts;

interface GatewayInterface
{
    /**
     * Is gateway active
     *
     * @return boolean
     */
    public function isActive(): bool;

    /**
     * Is gateway configured
     *
     * @return boolean
     */
    public function isConfigured(): bool;

    /**
     * set gateway config
     *
     * @return void
     */
    public function initialize();

    /**
     * Get name of the gateway
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get icon of the gateway
     *
     * @return string
     */
    public function getIcon(): string;

    /**
     * Return view file
     *
     * @return view
     */
    public function render();

    /**
     * process the payment method
     *
     * @return view
     */
    public function processPayment($transaction);

    /**
     * verify the payment
     *
     * @return boolean
     */
    public function verifyPayment($transaction, $request): bool;
}
