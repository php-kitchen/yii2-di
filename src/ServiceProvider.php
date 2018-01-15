<?php

namespace PHPKitchen\DI;

use PHPKitchen\DI\Mixins\ContainerAccess;
use PHPKitchen\DI\Mixins\ServiceLocatorAccess;
use yii\base\BaseObject;

/**
 * Base class for service providers that already includes container and
 * service locator mixins.
 *
 * @package PHPKitchen\DI
 */
abstract class ServiceProvider extends BaseObject implements Contracts\ServiceProvider {
    use ContainerAccess;
    use ServiceLocatorAccess;
}