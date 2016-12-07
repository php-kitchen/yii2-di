<?php
namespace tests;

use dekey\di\ClassFactory;
use dekey\di\Container;
use tests\base\TestCase;
use tests\stubs\ClassWithoutConstructor;
use tests\stubs\ClassWithoutConstructorDecorator;

/**
 * Unit test for {@link Container}
 *
 * @coversDefaultClass \dekey\di\Container
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ContainerTest extends TestCase {
    public function testConstruct() {
        $this->createContainer();
    }

    /**
     * @covers ::createFactoryFor
     */
    public function testCreateFactory() {
        $container = $this->createContainer();
        $factory = $container->createFactoryFor(static::class);
        $this->tester->checksScenario('instantiating default factory')
            ->expectsThat('container creates default factory for passed class')
            ->object($factory)
            ->isNotNull()
            ->isInstanceOf(ClassFactory::class);
    }

    /**
     * @covers ::configureObject
     */
    public function testConfigureObject() {
        $object = new ClassWithoutConstructor();
        $container = $this->createContainer();
        $container->configureObject($object, [
            'property' => 1,
            'anotherProperty' => 2,
        ]);
        $this->tester->checksScenario('instantiating default factory')
            ->expectsThat('container configures given object with passed properties')
            ->valueOf($object->property)
            ->isEqualTo(1)
            ->and()
            ->valueOf($object->anotherProperty)
            ->isEqualTo(2);
    }

    /**
     * @covers ::create
     * @covers ::addDecorator
     * @covers ::runDecoratorsOnObject
     * @covers ::isDecoratorsGroupRegistered
     * @covers \dekey\di\contracts\ObjectDecorator::decorate
     */
    public function testAddDecoratorAndCreate() {
        $container = $this->createContainer();
        $container->addDecorator(ClassWithoutConstructor::class, new ClassWithoutConstructorDecorator());

        $object = $container->create(ClassWithoutConstructor::class);
        $this->tester->checksScenario('registering decorator and instantiating object that should be decorated')
            ->expectsThat('decorator initialized object properties')
            ->valueOf($object->property)
            ->isEqualTo(1)
            ->and()
            ->valueOf($object->anotherProperty)
            ->isEqualTo(2);
    }

    public function testRegisterServiceProvider() {
        $container = $this->createContainer();
        $container->addDecorator(ClassWithoutConstructor::class, new ClassWithoutConstructorDecorator());

        $object = $container->create(ClassWithoutConstructor::class);
        $this->tester->checksScenario('registering decorator and instantiating object that should be decorated')
            ->expectsThat('decorator initialized object properties')
            ->valueOf($object->property)
            ->isEqualTo(1)
            ->and()
            ->valueOf($object->anotherProperty)
            ->isEqualTo(2);
    }

    protected function createContainer() {
        return new Container();
    }
}