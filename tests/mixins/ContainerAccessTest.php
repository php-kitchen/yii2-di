<?php
namespace tests\mixins;

use dekey\di\contracts\Container;
use dekey\di\contracts\ContainerAware;

use tests\base\TestCase;
use tests\mixins\stubs\ObjectWithContainerAccess;

/**
 * Unit test for {@link ContainerAccess}
 *
 * @coversDefaultClass \dekey\di\mixins\ContainerAccess
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ContainerAccessTest extends TestCase {
    public function testCreate() {
        $objectWithContainerAccess = $this->createObjectWithContainerAccess();
        $this->tester->checksScenario('instantiating object that use container access mixin')
            ->expectsThat('object implements container aware interface ')
            ->object($objectWithContainerAccess)
            ->isInstanceOf(ContainerAware::class);
    }

    /**
     * @covers ::getContainer
     * @covers ::initContainer
     * @covers ::setContainer
     */
    public function testGet() {
        $objectWithContainerAccess = $this->createObjectWithContainerAccess();
        $container = $objectWithContainerAccess->getContainer();
        $this->tester->checksScenario('acccessing container through container access mixin')
            ->expectsThat('object initialize return valid container that implements contract of container')
            ->object($container)
            ->isNotNull()
            ->isInstanceOf(Container::class);
    }

    protected function createObjectWithContainerAccess() {
        return new ObjectWithContainerAccess();
    }
}

