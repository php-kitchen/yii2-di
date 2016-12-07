<?php
namespace tests;

use dekey\di\ClassFactory;
use tests\base\TestCase;
use tests\stubs\ClassWithConstructor;
use tests\stubs\ClassWithoutConstructor;

/**
 * Unit test for {@link ClassFactory}
 *
 * @coversDefaultClass \dekey\di\ClassFactory
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassFactoryTest extends TestCase {
    public function testConstructor() {
        $factory = new ClassFactory();
    }

    /**
     * @covers ::setClassName
     * @covers ::getClassName
     * @covers ::create
     */
    public function testCreate() {
        $factory = new ClassFactory();
        $factory->setClassName(ClassWithoutConstructor::class);
        $object = $factory->create();

        $this->tester->checksScenario('instantiating object without any configuration')
            ->expectsThat('factory creates object of specified class without any configuration')
            ->object($object)
            ->isNotNull()
            ->isInstanceOf(ClassWithoutConstructor::class)
            ->and()
            ->valueOf($object->property)
            ->isNull();
    }

    /**
     * @covers ::setClassName
     * @covers ::getClassName
     * @covers ::create
     * @covers ::prepareObjectDefinitionFromConfig
     */
    public function testCreateWithConfig() {
        $factory = new ClassFactory();
        $factory->setClassName(ClassWithoutConstructor::class);
        $object = $factory->create(['property' => 1]);

        $this->tester->checksScenario('instantiating object with configuration passed to factory method')
            ->expectsThat('factory creates object of specified class and applies passed configuration')
            ->object($object)
            ->isNotNull()
            ->isInstanceOf(ClassWithoutConstructor::class)
            ->and()
            ->valueOf($object->property)
            ->isEqualTo(1)
            ->and()
            ->valueOf($object->anotherProperty)
            ->isNull();
    }

    /**
     * @covers ::setClassName
     * @covers ::getClassName
     * @covers ::setDefaultConfig
     * @covers ::getDefaultConfig
     * @covers ::create
     * @covers ::prepareObjectDefinitionFromConfig
     */
    public function testCreateWithDefaultConfig() {
        $factory = new ClassFactory();
        $factory->setClassName(ClassWithoutConstructor::class);
        $factory->setDefaultConfig(['property' => 1]);
        $object = $factory->create(['anotherProperty' => 2]);

        $this->tester->checksScenario('instantiating object with configuration passed to factory method and specified as default')
            ->expectsThat('factory creates object of specified class and applies default and passed configuration')
            ->object($object)
            ->isNotNull()
            ->isInstanceOf(ClassWithoutConstructor::class)
            ->and()
            ->valueOf($object->property)
            ->isEqualTo(1)
            ->and()
            ->valueOf($object->anotherProperty)
            ->isEqualTo(2);
    }

    /**
     * @covers ::setClassName
     * @covers ::getClassName
     * @covers ::setDefaultConfig
     * @covers ::getDefaultConfig
     * @covers ::setDefaultConstructorParams
     * @covers ::getDefaultConstructorParams
     * @covers ::create
     * @covers ::prepareObjectDefinitionFromConfig
     */
    public function testCreateWithDefaultConfigAndDefaultConstructorParams() {
        $factory = new ClassFactory();
        $factory->setClassName(ClassWithConstructor::class);
        $factory->setDefaultConfig(['property' => 1]);
        $dependency = new ClassWithoutConstructor();
        $factory->setDefaultConstructorParams([$dependency]);
        $object = $factory->create();

        $this->tester->checksScenario('instantiating object with default configuration and default constructor params')
            ->expectsThat('factory creates object of specified class and applies default configuration and default constructor params')
            ->object($object)
            ->isNotNull()
            ->isInstanceOf(ClassWithConstructor::class)
            ->and()
            ->valueOf($object->property)
            ->isEqualTo(1)
            ->and()
            ->valueOf($object->getDependency())
            ->isEqualTo($dependency);
    }

    /**
     * @covers ::setClassName
     * @covers ::getClassName
     * @covers ::setDefaultConfig
     * @covers ::getDefaultConfig
     * @covers ::setDefaultConstructorParams
     * @covers ::getDefaultConstructorParams
     * @covers ::createWithConstructorParams
     * @covers ::prepareObjectDefinitionFromConfig
     */
    public function testCreateWithConstructorParamsAndWithDefaultConfig() {
        $factory = new ClassFactory();
        $factory->setClassName(ClassWithConstructor::class);
        $factory->setDefaultConfig(['property' => 1]);
        $defaultDependency = new ClassWithoutConstructor();
        $factory->setDefaultConstructorParams([$defaultDependency]);

        $dependency = new ClassWithoutConstructor();
        $dependency->property = 1;
        $dependency->anotherProperty = 45;
        $object = $factory->createWithConstructorParams([$dependency]);

        $this->tester->checksScenario('instantiating object with specified default configuration and default constructor params and constructor params passed to factory method')
            ->expectsThat('factory creates object of specified class and applies default configuration but overrides default constructor params by constructor params passed to factory method')
            ->object($object)
            ->isNotNull()
            ->isInstanceOf(ClassWithConstructor::class)
            ->and()
            ->valueOf($object->property)
            ->isEqualTo(1)
            ->and()
            ->valueOf($object->getDependency())
            ->isEqualTo($dependency);
    }
}