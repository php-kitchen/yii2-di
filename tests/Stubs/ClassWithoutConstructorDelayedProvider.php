<?php

namespace PHPKitchen\DI\Tests\Stubs;

use PHPKitchen\DI\DelayedServiceProvider;

class ClassWithoutConstructorDelayedProvider extends DelayedServiceProvider {
    public function listProvidedClasses() {
        return [
            ClassWithoutConstructor::class,
        ];
    }

    public function register() {
        $this->container->set(ClassWithoutConstructor::class, ClassWithoutConstructor::class);
    }
}