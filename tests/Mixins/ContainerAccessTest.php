<?php

namespace PHPKitchen\DI\Tests\Mixins;

use PHPKitchen\DI\Contracts\Container;
use PHPKitchen\DI\Contracts\ContainerAware;
use PHPKitchen\DI\Tests\Base\TestCase;
use PHPKitchen\DI\Tests\Mixins\Stubs\ObjectWithContainerAccess;

/**
 * Unit test for {@link ContainerAccess}
 *
 * @coversDefaultClass \PHPKitchen\DI\mixins\ContainerAccess
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ContainerAccessTest extends TestCase {
    public function testCreate(): void {
        $objectWithContainerAccess = $this->createObjectWithContainerAccess();
        $this->tester->describe('instantiating object that use container access mixin')
                     ->expectThat('object implements container aware interface ')
                     ->seeObject($objectWithContainerAccess)
                     ->isInstanceOf(ContainerAware::class);
    }

    /**
     * @covers ::getContainer
     * @covers ::initContainer
     * @covers ::setContainer
     */
    public function testGet(): void {
        $objectWithContainerAccess = $this->createObjectWithContainerAccess();
        $container = $objectWithContainerAccess->getContainer();
        $this->tester->describe('acccessing container through container access mixin')
                     ->expectThat('object initialize return valid container that implements contract of container')
                     ->seeObject($container)
                     ->isNotNull()
                     ->isInstanceOf(Container::class);
    }

    protected function createObjectWithContainerAccess(): ObjectWithContainerAccess {
        return new ObjectWithContainerAccess();
    }
}

