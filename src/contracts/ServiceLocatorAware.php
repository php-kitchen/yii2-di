<?php

namespace dekey\di\contracts;

use yii\di\ServiceLocator;

/**
 * Defines interface for classes that aware of service locator.
 *
 * @property ServiceLocator|\yii\base\Application $serviceLocator alias of getter and setter methods.
 *
 * @package dekey\di\contracts
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
interface ServiceLocatorAware {
    public function getServiceLocator();

    public function setServiceLocator(ServiceLocator $locator);
}