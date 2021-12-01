<?php

namespace PHPKitchen\DI\Mixins;

use PHPKitchen\DI\Contracts\Container;
use Yii;

/**
 * Injects DI container to target class.
 *
 * @property Container $container public alias of {@link _diContainer}
 *
 * @package PHPKitchen\DI\mixins
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
trait ContainerAccess {
    protected ?Container $_container = null;

    public function getContainer(): Container {
        if (!isset($this->_container)) {
            $this->initContainer();
        }

        return $this->_container;
    }

    public function setContainer(Container $container): void {
        $this->_container = $container;
    }

    protected function initContainer(): void {
        $this->setContainer(Yii::$container);
    }
}
