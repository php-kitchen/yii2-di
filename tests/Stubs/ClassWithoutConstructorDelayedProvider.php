<?php

namespace PHPKitchen\DI\Tests\Stubs;

use PHPKitchen\DI\DelayedServiceProvider;

class ClassWithoutConstructorDelayedProvider extends DelayedServiceProvider {
    public function listProvidedClasses(): array {
        return [
            ClassWithoutConstructor::class,
        ];
    }

    public function register(): void {
        $this->container->set(ClassWithoutConstructor::class, ClassWithoutConstructor::class);
    }
}
