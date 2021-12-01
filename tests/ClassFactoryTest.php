<?php

namespace PHPKitchen\DI\Tests;

use PHPKitchen\DI\ClassFactory;
use PHPKitchen\DI\Tests\Base\TestCase;
use PHPKitchen\DI\Tests\Stubs\ClassWithConstructor;
use PHPKitchen\DI\Tests\Stubs\ClassWithoutConstructor;
use yii\base\InvalidConfigException;

/**
 * Unit test for {@link ClassFactory}
 *
 * @coversDefaultClass \PHPKitchen\DI\ClassFactory
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassFactoryTest extends TestCase {
    public function testConstructor(): void {
        $factory = new ClassFactory();
        $this->tester->seeObject($factory)
                     ->isInstanceOf(ClassFactory::class);
    }

    /**
     * @covers ::setClassName
     * @covers ::getClassName
     * @covers ::create
     * @throws InvalidConfigException
     */
    public function testCreate(): void {
        $factory = new ClassFactory();
        $factory->setClassName(ClassWithoutConstructor::class);
        $object = $factory->create();

        $this->tester->describe('instantiating object without any configuration')
                     ->expectThat('factory creates object of specified class without any configuration')
                     ->seeObject($object)
                     ->isNotNull()
                     ->isInstanceOf(ClassWithoutConstructor::class);
        $this->tester->see($object->property)
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

        $this->tester->describe('instantiating object with configuration passed to factory method')
                     ->expectThat('factory creates object of specified class and applies passed configuration')
                     ->seeObject($object)
                     ->isNotNull()
                     ->isInstanceOf(ClassWithoutConstructor::class);
        $this->tester->see($object->property)
                     ->isEqualTo(1);
        $this->tester->see($object->anotherProperty)
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

        $this->tester->describe('instantiating object with configuration passed to factory method and specified as default')
                     ->expectThat('factory creates object of specified class and applies default and passed configuration')
                     ->seeObject($object)
                     ->isNotNull()
                     ->isInstanceOf(ClassWithoutConstructor::class);
        $this->tester->see($object->property)
                     ->isEqualTo(1);
        $this->tester->see($object->anotherProperty)
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

        $this->tester->describe('instantiating object with default configuration and default constructor params')
                     ->expectThat('factory creates object of specified class and applies default configuration and default constructor params')
                     ->seeObject($object)
                     ->isNotNull()
                     ->isInstanceOf(ClassWithConstructor::class);
        $this->tester->see($object->property)
                     ->isEqualTo(1);
        $this->tester->see($object->getDependency())
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

        $this->tester->describe('instantiating object with specified default configuration and default constructor params and constructor params passed to factory method')
                     ->expectThat('factory creates object of specified class and applies default configuration but overrides default constructor params by constructor params passed to factory method')
                     ->seeObject($object)
                     ->isNotNull()
                     ->isInstanceOf(ClassWithConstructor::class);
        $this->tester->see($object->property)
                     ->isEqualTo(1);
        $this->tester->see($object->getDependency())
                     ->isEqualTo($dependency);
    }
}
