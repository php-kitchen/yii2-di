<?php

namespace tests\base;

include __DIR__ . '/../../src/Autoload/ClassLoader.php';
use PHPKitchen\DI\Autoload\ClassGenerator;
use tests\stubs\ConfigurableClass;

/**
 * Represents base class for autoload related tests.
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
abstract class VirtualClassGenerationTest extends TestCase {
    const EXISTING_VIRTUAL_CLASS = 'tests\autoload\VirtualClass';
    const NOT_EXISTING_VIRTUAL_CLASS = 'tests\autoload\SecondVirtualClass';
    const NOT_EXISTING_VIRTUAL_CLASS_FILE_NAME = __DIR__ . '/../runtime/tests_autoload_SecondVirtualClass__tests_stubs_ConfigurableClass.php';

    protected function createGenerator() {
        return new ClassGenerator();
    }

    /**
     * @before
     */
    protected function setUpDefinitionsInContainer() {
        $this->container->set(self::EXISTING_VIRTUAL_CLASS, [
            'class' => ConfigurableClass::class,
        ]);
        $this->container->set(self::NOT_EXISTING_VIRTUAL_CLASS, ConfigurableClass::class);
    }

    /**
     * @before
     */
    protected function removeGeneratedFilesIfExists() {
        $fileName = self::NOT_EXISTING_VIRTUAL_CLASS_FILE_NAME;
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    }
}