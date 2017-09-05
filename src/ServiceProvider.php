<?php

namespace dekey\di;

use dekey\di\mixins\ContainerAccess;
use dekey\di\mixins\ServiceLocatorAccess;
use yii\base\Object;

/**
 * Base class for service providers that already includes container and
 * service locator mixins.
 *
 * @package dekey\di
 */
abstract class ServiceProvider extends Object implements contracts\ServiceProvider {
    use ContainerAccess;
    use ServiceLocatorAccess;
}