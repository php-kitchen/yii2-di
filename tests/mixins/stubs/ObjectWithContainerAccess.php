<?php
namespace tests\mixins\Stubs;

use PHPKitchen\DI\Contracts\ContainerAware;
use PHPKitchen\DI\Mixins\ContainerAccess;

class ObjectWithContainerAccess implements ContainerAware {
    use ContainerAccess;
}