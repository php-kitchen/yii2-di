<?php

namespace PHPKitchen\DI\Tests\Stubs;

use PHPKitchen\DI\ServiceProvider;

class ClassWithoutConstructorProvider extends ServiceProvider {
    public function register() {
        $this->container->set(ClassWithoutConstructor::class, ClassWithoutConstructor::class);
    }
}