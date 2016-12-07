<?php
namespace tests\mixins\Stubs;

use dekey\di\contracts\ContainerAware;
use dekey\di\mixins\ContainerAccess;

class ObjectWithContainerAccess implements ContainerAware {
    use ContainerAccess;
}