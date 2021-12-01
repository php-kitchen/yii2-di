<?php

namespace PHPKitchen\DI\Autoload;

use Yii;

include __DIR__ . '/ClassGenerator.php';

/**
 * Represents auto-loader that load virtual classes defined in IoC Container.
 *
 * @package PHPKitchen\DI\autoload
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassLoader {
    private static ?ClassLoader $instance = null;
    private ClassGenerator $classGenerator;

    public function __construct($classGenerator) {
        $this->classGenerator = $classGenerator;
    }

    public static function getInstance(): ClassLoader {
        if (null === self::$instance) {
            self::$instance = new static(new ClassGenerator());
        }

        return self::$instance;
    }

    public static function loadClass($className): void {
        if (!Yii::$container) {
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
