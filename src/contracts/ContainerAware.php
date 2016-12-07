<?php

namespace dekey\di\contracts;

/**
 * Defines interface for classes that aware of DI container.
 *
 * @property Container $diContainer alias of getter and setter method.
 *
 * @package dekey\di\contracts
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
interface ContainerAware {
    public function getContainer();

    public function setContainer(Container $container);
}