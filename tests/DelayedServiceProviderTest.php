<?php

namespace PHPKitchen\DI\Tests;

use PHPKitchen\DI\Container;
use PHPKitchen\DI\Tests\Base\TestCase;
use PHPKitchen\DI\Tests\Stubs\ClassWithoutConstructor;
use PHPKitchen\DI\Tests\Stubs\ClassWithoutConstructorDelayedProvider;

/**
 * Test for {@link \PHPKitchen\DI\DelayedServiceProvider}
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class DelayedServiceProviderTest extends TestCase {
    public function testServiceProviderDelay() {
        $container = new Container();
        \Yii::$container = $container;

        $tester = $this->tester;
        $tester->expectsThat('Container do not have class registered before service provider added.')
               ->boolean($container->has(ClassWithoutConstructor::class))
               ->isFalse();

        $container->addProvider(ClassWithoutConstructorDelayedProvider::class);

        $tester->expectsThat('Container do not have class registered after delayed service provider added.')
               ->boolean($container->has(ClassWithoutConstructor::class))
               ->isFalse();

        $container->get(ClassWithoutConstructor::class);

        $tester->expectsThat('Provider registered class once it was requested from the container.')
               ->boolean($container->has(ClassWithoutConstructor::class))
               ->isTrue();
    }
}