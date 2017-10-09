<?php

namespace PHPKitchen\DI;

use PHPKitchen\DI\Mixins\ContainerAccess;
use PHPKitchen\DI\Mixins\ServiceLocatorAccess;
use yii\base\Object;

/**
 * Base class for service providers that already includes container and
 * service locator mixins.
 *
 * @package PHPKitchen\DI
 */
abstract class ServiceProvider extends Object implements Contracts\ServiceProvider {
    use ContainerAccess;
    use ServiceLocatorAccess;
}