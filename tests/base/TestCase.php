<?php
namespace tests\base;

use DeKey\Tester\TesterInitialization;

/**
 * Represents base class for all of the test cases.
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class TestCase extends \PHPUnit\Framework\TestCase {
    use TesterInitialization;
    /**
     * @var \PHPKitchen\DI\Container
     */
    protected $container;

    protected function setUp() {
        $this->container = \Yii::$container;
        parent::setUp();
    }

    /**
     * @before
     */
    protected function configureApplication() {
        \Yii::$app->runtimePath = __DIR__ . '/../runtime';
    }
}