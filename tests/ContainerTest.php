<?php

namespace PHPKitchen\DI\Tests;

use PHPKitchen\DI\ClassFactory;
use PHPKitchen\DI\Container;
use PHPKitchen\DI\Tests\Base\TestCase;
use PHPKitchen\DI\Tests\Stubs\ClassWithoutConstructor;
use PHPKitchen\DI\Tests\Stubs\ClassWithoutConstructorDecorator;
use yii\base\InvalidConfigException;

/**
 * Unit test for {@link Container}
 *
 * @coversDefaultClass \PHPKitchen\DI\Container
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ContainerTest extends TestCase {
    public function testConstruct(): void {
        $container = $this->createContainer();
        $this->tester->seeObject($container)
                     ->isInstanceOf(Container::class);
    }

    /**
     * @covers ::createFactoryFor
     */
    public function testCreateFactory(): void {
        $container = $this->createContainer();
        $factory = $container->createFactoryFor(static::class);
        $this->tester->describe('instantiating default factory')
                     ->expectThat('container creates default factory for passed class')
                     ->seeObject($factory)
                     ->isNotNull()
                     ->isInstanceOf(ClassFactory::class);
    }

    /**
     * @covers ::configureObject
     */
    public function testConfigureObject(): void {
        $object = new ClassWithoutConstructor();
        $container = $this->createContainer();
        $container->configureObject($object, [
            'property' => 1,
            'anotherProperty' => 2,
        ]);
        $this->tester->describe('instantiating default factory')
                     ->expectThat('container configures given object with passed properties')
                     ->see($object->property)
                     ->isEqualTo(1);
        $this->tester->see($object->anotherProperty)
                     ->isEqualTo(2);
    }

    /**
     * @covers ::create
     * @covers ::addDecorator
     * @covers ::runDecoratorsOnObject
     * @covers ::isDecoratorsGroupRegistered
     * @covers \PHPKitchen\DI\Contracts\ObjectDecorator::decorate
     * @throws InvalidConfigException
     */
    public function testAddDecoratorAndCreate(): void {
        $container = $this->createContainer();
        $container->addDecorator(ClassWithoutConstructor::class, new ClassWithoutConstructorDecorator());

        $object = $container->create(ClassWithoutConstructor::class);
        $this->tester->describe('registering decorator and instantiating object that should be decorated')
                     ->expectThat('decorator initialized object properties')
                     ->see($object->property)
                     ->isEqualTo(1);
        $this->tester->see($object->anotherProperty)
                     ->isEqualTo(2);
    }

    /**
     * @throws InvalidConfigException
     */
    public function testRegisterServiceProvider(): void {
        $container = $this->createContainer();
        $container->addDecorator(ClassWithoutConstructor::class, new ClassWithoutConstructorDecorator());

        $object = $container->create(ClassWithoutConstructor::class);
        $this->tester->describe('registering decorator and instantiating object that should be decorated')
                     ->expectThat('decorator initialized object properties')
                     ->see($object->property)
                     ->isEqualTo(1);
        $this->tester->see($object->anotherProperty)
                     ->isEqualTo(2);
    }

    protected function createContainer(): Container {
        return new Container();
    }
}
