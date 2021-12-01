<?php

namespace PHPKitchen\DI\Tests\Mixins\Stubs;

use PHPKitchen\DI\Contracts\ServiceLocatorAware;
use PHPKitchen\DI\Mixins\ServiceLocatorAccess;

class ObjectWithServiceLocatorAccess implements ServiceLocatorAware {
    use ServiceLocatorAccess;
}
