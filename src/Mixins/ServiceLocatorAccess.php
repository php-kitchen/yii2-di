<?php

namespace PHPKitchen\DI\Mixins;

use core\app\Application;
use yii\di\ServiceLocator;

/**
 * Injects service locator to target class.
 * Use this trait only if you can't use dependency injection container.
 *
 * @property ServiceLocator|\yii\base\Application $serviceLocator public alias of {@link _serviceLocator}
 *
 * @package PHPKitchen\DI\mixins
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
trait ServiceLocatorAccess {
    /**
     * @var ServiceLocator|\yii\base\Application
     */
    protected $_serviceLocator;

    public function getServiceLocator() {
        if (!isset($this->_serviceLocator)) {
            $this->initServiceLocator();
        }
        return $this->_serviceLocator;
    }

    public function setServiceLocator(ServiceLocator $locator) {
        $this->_serviceLocator = $locator;
    }

    protected function initServiceLocator() {
        $this->setServiceLocator(\Yii::$app);
    }
}