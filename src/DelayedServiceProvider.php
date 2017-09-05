<?php

namespace dekey\di;

/**
 * Base class for service providers that should be delayed to register till services are
 * actually required.
 *
 * @property array $providedClasses private alias of {@link _providedClasses}
 *
 * @package dekey\di
 */
abstract class DelayedServiceProvider extends ServiceProvider implements contracts\DelayedServiceProvider {
    private $_providedClasses;

    /**
     *
     * @return array list of classes provided
     */
    abstract protected function listProvidedClasses();

    public function provides($classOrInterface) {
        return in_array($classOrInterface, $this->providedClasses);
    }

    protected function getProvidedClasses() {
        if (null === $this->_providedClasses) {
            $this->_providedClasses = $this->listProvidedClasses();
        }
        return $this->_providedClasses;
    }
}