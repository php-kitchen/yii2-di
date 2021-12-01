<?php

namespace PHPKitchen\DI\Contracts;

/**
 * Represents "Factory" pattern.
 *
 * @see https://sourcemaking.com/design_patterns/abstract_factory
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
interface Factory {
    public function create(): void;

    public function setDefaultConfig(array $config): void;
}
