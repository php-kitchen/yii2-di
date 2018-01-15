<?php

namespace PHPKitchen\DI\Tests\Stubs;

use PHPKitchen\DI\Contracts\ObjectDecorator;

/**
 * Represents a decorator of {@link ClassWithoutConstructor} being used for
 * container tests.
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassWithoutConstructorDecorator implements ObjectDecorator {
    /**
     * @param ClassWithoutConstructor $object
     */
    public function decorate($object) {
        $object->property = 1;
        $object->anotherProperty = 2;
    }
}