<?php
namespace dekey\di\contracts;

/**
 * Represents "Factory" pattern.
 *
 * @see https://sourcemaking.com/design_patterns/abstract_factory
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
interface Factory {
    public function create();

    public function setDefaultConfig($config);
}