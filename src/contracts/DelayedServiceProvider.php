<?php

namespace dekey\di\contracts;

/**
 * Service provider that should be delayed to register till services are
 * actually required.
 *
 * @package dekey\di\contracts
 */
interface DelayedServiceProvider extends ServiceProvider {
    public function provides($classOrInterface);
}