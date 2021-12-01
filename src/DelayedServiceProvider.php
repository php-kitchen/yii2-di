<?php

namespace PHPKitchen\DI;

/**
 * Base class for service providers that should be delayed to register till services are
 * actually required.
 *
 * @property array $providedClasses private alias of {@link _providedClasses}
 *
 * @package PHPKitchen\DI
 */
abstract class DelayedServiceProvider extends ServiceProvider implements Contracts\DelayedServiceProvider {
    private ?array $_providedClasses = null;

    /**
     *
     * @return array list of classes provided
     */
    abstract protected function listProvidedClasses(): array;

    public function provides($classOrInterface): bool {
        return in_array($classOrInterface, $this->providedClasses, true);
    }

    protected function getProvidedClasses(): array {
        if (null === $this->_providedClasses) {
            $this->_providedClasses = $this->listProvidedClasses();
        }

        return $this->_providedClasses;
    }
}
