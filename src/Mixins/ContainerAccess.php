<?php

namespace PHPKitchen\DI\Mixins;

use PHPKitchen\DI\Contracts\Container;

/**
 * Injects DI container to target class.
 *
 * @property Container $container public alias of {@link _diContainer}
 *
 * @package PHPKitchen\DI\mixins
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
trait ContainerAccess {
    /**
     * @var Container
     */
    protected $_container;

    public function getContainer() {
        if (!isset($this->_container)) {
            $this->initContainer();
        }

        return $this->_container;
    }

    public function setContainer(Container $container) {
        $this->_container = $container;
    }

    protected function initContainer() {
        $this->setContainer(\Yii::$container);
    }
}