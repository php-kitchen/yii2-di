<?php

namespace PHPKitchen\DI\Tests;

use PHPKitchen\DI\Container;
use PHPKitchen\DI\Tests\Base\TestCase;
use PHPKitchen\DI\Tests\Stubs\ClassWithoutConstructor;
use PHPKitchen\DI\Tests\Stubs\ClassWithoutConstructorProvider;
use Yii;

/**
 * Test for {@link Container} and {@link \PHPKitchen\DI\ServiceProvider}
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ServiceProviderTest extends TestCase {
    public function testAddProviderByClassName() {
        $container = new Container();
        Yii::$container = $container;

        $tester = $this->tester;
        $tester->expectThat('Container should not have class registered before service provider added.')
               ->seeBool($container->has(ClassWithoutConstructor::class))
               ->isFalse();

        $container->addProvider(ClassWithoutConstructorProvider::class);

        $tester->expectThat('Provider registered class once it was added to container.')
               ->seeBool($container->has(ClassWithoutConstructor::class))
               ->isTrue();
    }
}
