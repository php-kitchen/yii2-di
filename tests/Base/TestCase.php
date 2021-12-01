<?php

namespace PHPKitchen\DI\Tests\Base;

use PHPKitchen\CodeSpecs\Mixin\TesterInitialization;
use PHPKitchen\DI\Container;
use Yii;

/**
 * Represents base class for all the test cases.
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class TestCase extends \PHPUnit\Framework\TestCase {
    use TesterInitialization;

    /**
     * @var Container
     */
    protected $container;

    protected function setUp(): void {
        $this->container = Yii::$container;
        parent::setUp();
    }

    /**
     * @before
     */
    protected function configureApplication(): void {
        Yii::$app->runtimePath = __DIR__ . '/../runtime';
    }
}
