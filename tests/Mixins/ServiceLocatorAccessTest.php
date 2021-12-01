<?php

namespace PHPKitchen\DI\Tests\Mixins;

use PHPKitchen\DI\Contracts\ServiceLocatorAware;
use PHPKitchen\DI\Tests\Base\TestCase;
use PHPKitchen\DI\Tests\Mixins\Stubs\ObjectWithServiceLocatorAccess;
use yii\di\ServiceLocator;

/**
 * Unit test for {@link ServiceLocatorAccess}
 *
 * @coversDefaultClass \PHPKitchen\DI\mixins\ServiceLocatorAccess
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ServiceLocatorAccessTest extends TestCase {
    public function testCreate(): void {
        $objectWithServiceLocatorAccess = $this->createObjectWithServiceLocatorAccess();
        $this->tester->describe('instantiating object that use service locator access mixin')
                     ->expectThat('object implements service locator aware interface ')
                     ->seeObject($objectWithServiceLocatorAccess)
                     ->isInstanceOf(ServiceLocatorAware::class);
    }

    /**
     * @covers ::getServiceLocator
     * @covers ::initServiceLocator
     * @covers ::setServiceLocator
     */
    public function testGet(): void {
        $objectWithServiceLocatorAccess = $this->createObjectWithServiceLocatorAccess();
        $container = $objectWithServiceLocatorAccess->getServiceLocator();
        $this->tester->describe('acccessing service locator through service locator access mixin')
                     ->expectThat('object initialize and return valid service locator that implements contract of service locator')
                     ->seeObject($container)
                     ->isNotNull()
                     ->isInstanceOf(ServiceLocator::class);
    }

    protected function createObjectWithServiceLocatorAccess(): ObjectWithServiceLocatorAccess {
        return new ObjectWithServiceLocatorAccess();
    }
}


