<?php

namespace PHPKitchen\DI\Mixins;

use Yii;
use yii\di\ServiceLocator;

/**
 * Injects service locator to target class.
 * Use this trait only if you can't use dependency injection container.
 *
 * @property ServiceLocator $serviceLocator public alias of {@link _serviceLocator}
 *
 * @package PHPKitchen\DI\mixins
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
trait ServiceLocatorAccess {
    protected ?ServiceLocator $_serviceLocator = null;

    public function getServiceLocator(): ServiceLocator {
        if (!isset($this->_serviceLocator)) {
            $this->initServiceLocator();
        }

        return $this->_serviceLocator;
    }

    public function setServiceLocator(ServiceLocator $locator): void {
        $this->_serviceLocator = $locator;
    }

    protected function initServiceLocator(): void {
        $this->setServiceLocator(Yii::$app);
    }
}
