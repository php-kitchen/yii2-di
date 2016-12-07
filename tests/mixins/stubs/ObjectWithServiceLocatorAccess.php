<?php
namespace tests\mixins\stubs;

use dekey\di\contracts\ServiceLocatorAware;
use dekey\di\mixins\ServiceLocatorAccess;

class ObjectWithServiceLocatorAccess implements ServiceLocatorAware {
    use ServiceLocatorAccess;
}