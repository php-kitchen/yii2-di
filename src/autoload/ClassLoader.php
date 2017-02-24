<?php

namespace dekey\di\autoload;

include __DIR__ . '/ClassGenerator.php';

/**
 * Represents auto-loader that load virtual classes defined in IoC Container.
 *
 * @package dekey\di\autoload
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassLoader {
    private static $instance;
    /**
     * @var ClassGenerator
     */
    private $classGenerator;

    public function __construct($classGenerator) {
        $this->classGenerator = $classGenerator;
    }

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new static(new ClassGenerator());
        }
        return self::$instance;
    }

    public static function loadClass($className) {
        if (!\Yii::$container) {
            return;
        }
        $classFileName = self::getInstance()->tryToGetClassFileOrGenerate($className);
        if ($classFileName && file_exists($classFileName)) {
            include $classFileName;
        }
    }

    protected function tryToGetClassFileOrGenerate($class) {
        $generator = $this->classGenerator;
        return $generator->getClassFileNameIfExistOrGenerate($class);
    }
}