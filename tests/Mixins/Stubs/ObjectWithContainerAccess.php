<?php

namespace PHPKitchen\DI\Tests\Mixins\Stubs;

use PHPKitchen\DI\Contracts\ContainerAware;
use PHPKitchen\DI\Mixins\ContainerAccess;

class ObjectWithContainerAccess implements ContainerAware {
    use ContainerAccess;
}