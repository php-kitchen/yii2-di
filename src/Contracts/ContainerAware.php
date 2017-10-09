<?php

namespace PHPKitchen\DI\Contracts;

/**
 * Defines interface for classes that aware of DI container.
 *
 * @property Container $diContainer alias of getter and setter method.
 *
 * @package PHPKitchen\DI\contracts
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
interface ContainerAware {
    public function getContainer();

    public function setContainer(Container $container);
}