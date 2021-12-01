<?php

namespace PHPKitchen\DI\Tests\Stubs;

/**
 * Represents a stub being used for testing of {@link \PHPKitchen\DI\ClassFactory} functionality.
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassWithConstructor {
    public $property;
    protected ClassWithoutConstructor $dependency;

    public function __construct(ClassWithoutConstructor $dependency) {
        $this->dependency = $dependency;
    }

    public function getDependency(): ClassWithoutConstructor {
        return $this->dependency;
    }
}
