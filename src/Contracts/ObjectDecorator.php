<?php
/**
 * Created by PhpStorm.
 * User: dange
 * Date: 16-Sep-16
 * Time: 14:26
 */

namespace PHPKitchen\DI\Contracts;

/**
 * Represents decorator of any objects.
 *
 * @see https://sourcemaking.com/design_patterns/decorator
 *
 * @package PHPKitchen\DI\contracts
 */
interface ObjectDecorator {
    public function decorate($object);
}