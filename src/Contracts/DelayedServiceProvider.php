<?php

namespace PHPKitchen\DI\Contracts;

/**
 * Service provider that should be delayed to register till services are
 * actually required.
 *
 * @package PHPKitchen\DI\contracts
 */
interface DelayedServiceProvider extends ServiceProvider {
    public function provides($classOrInterface);
}