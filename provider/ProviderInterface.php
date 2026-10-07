<?php
/**
 * SMM Panel - Provider Interface
 * Standard SMM Provider API Protocol (API v2)
 */

interface ProviderInterface {
    /**
     * Fetch current provider account balance
     */
    public function getBalance(): array;

    /**
     * Fetch list of all services provided by this API
     */
    public function getServices(): array;

    /**
     * Dispatch an order to the provider API
     */
    public function addOrder(string|int $serviceId, string $link, int $quantity, array $extraParams = []): array;

    /**
     * Fetch order execution status by provider order ID
     */
    public function getOrderStatus(string|int $providerOrderId): array;

    /**
     * Fetch multiple order execution statuses (comma-separated ID sync)
     */
    public function getMultipleOrderStatus(array $providerOrderIds): array;
}
