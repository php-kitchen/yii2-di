<?php

namespace tests\autoload;

use PHPKitchen\DI\Autoload\ClassLoader;
use tests\base\VirtualClassGenerationTest;

/**
 * Unit test for {@link ClassLoader}
 *
 * @coversDefaultClass \PHPKitchen\DI\autoload\ClassLoader
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassLoaderTest extends VirtualClassGenerationTest {
    /**
     * @covers ::<public>
     * @covers ::<protected>
     */
    public function testAutoloadExistingClass() {
        $existingClassName = self::EXISTING_VIRTUAL_CLASS;
        $virtualClassInstance = new $existingClassName;
    }

    /**
     * @covers ::<public>
     * @covers ::<protected>
     */
    public function testAutoloadNotExistingClass() {
        $existingClassName = self::NOT_EXISTING_VIRTUAL_CLASS;
        $virtualClassInstance = new $existingClassName;
    }

    /**
     * @before
     */
    protected function registerAutoloadFunction() {
        spl_autoload_register([ClassLoader::class, 'loadClass'], true, false);
    }

    /**
     * @after
     */
    protected function unRegisterAutoloadFunction() {
        spl_autoload_unregister([ClassLoader::class, 'loadClass']);
    }
}