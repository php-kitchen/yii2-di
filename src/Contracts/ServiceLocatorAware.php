<?php

namespace PHPKitchen\DI\Contracts;

use yii\base\Application;
use yii\di\ServiceLocator;

/**
 * Defines interface for classes that aware of service locator.
 *
 * @property ServiceLocator|Application $serviceLocator alias of getter and setter methods.
 *
 * @package PHPKitchen\DI\contracts
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
interface ServiceLocatorAware {
    public function getServiceLocator(): ServiceLocator;

    public function setServiceLocator(ServiceLocator $locator);
}
