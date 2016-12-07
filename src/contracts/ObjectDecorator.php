<?php
/**
 * Created by PhpStorm.
 * User: dange
 * Date: 16-Sep-16
 * Time: 14:26
 */

namespace dekey\di\contracts;

/**
 * Represents decorator of any objects.
 *
 * @see https://sourcemaking.com/design_patterns/decorator
 *
 * @package dekey\di\contracts
 */
interface ObjectDecorator {
    public function decorate($object);
}