<?php
namespace tests\mixins;

use PHPKitchen\DI\Contracts\ServiceLocatorAware;
use yii\di\ServiceLocator;
use tests\base\TestCase;
use tests\mixins\stubs\ObjectWithServiceLocatorAccess;

/**
 * Unit test for {@link ServiceLocatorAccess}
 *
 * @coversDefaultClass \PHPKitchen\DI\mixins\ServiceLocatorAccess
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ServiceLocatorAccessTest extends TestCase {
    public function testCreate() {
        $objectWithServiceLocatorAccess = $this->createObjectWithServiceLocatorAccess();
        $this->tester->checksScenario('instantiating object that use service locator access mixin')
            ->expectsThat('object implements service locator aware interface ')
            ->object($objectWithServiceLocatorAccess)
            ->isInstanceOf(ServiceLocatorAware::class);
    }

    /**
     * @covers ::getServiceLocator
     * @covers ::initServiceLocator
     * @covers ::setServiceLocator
     */
    public function testGet() {
        $objectWithServiceLocatorAccess = $this->createObjectWithServiceLocatorAccess();
        $container = $objectWithServiceLocatorAccess->getServiceLocator();
        $this->tester->checksScenario('acccessing service locator through service locator access mixin')
            ->expectsThat('object initialize and return valid service locator that implements contract of service locator')
            ->object($container)
            ->isNotNull()
            ->isInstanceOf(ServiceLocator::class);
    }

    protected function createObjectWithServiceLocatorAccess() {
        return new ObjectWithServiceLocatorAccess();
    }
}


