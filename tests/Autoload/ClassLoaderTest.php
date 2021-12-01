<?php

namespace PHPKitchen\DI\Tests\Autoload;

use PHPKitchen\DI\Autoload\ClassLoader;
use PHPKitchen\DI\Tests\Base\VirtualClassGenerationTest;

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
    public function testAutoloadExistingClass(): void {
        $existingClassName = self::EXISTING_VIRTUAL_CLASS;
        $virtualClassInstance = new $existingClassName;
        $tester = $this->tester;
        $tester->expectThat('loader successfully load existing virtual class file')
               ->seeObject($virtualClassInstance)
               ->isInstanceOf($existingClassName);
    }

    /**
     * @covers ::<public>
     * @covers ::<protected>
     */
    public function testAutoloadNotExistingClass(): void {
        $existingClassName = self::NOT_EXISTING_VIRTUAL_CLASS;
        $virtualClassInstance = new $existingClassName;
        $tester = $this->tester;
        $tester->expectThat('loader successfully load not existing virtual class file')
               ->seeObject($virtualClassInstance)
               ->isInstanceOf($existingClassName);
    }

    /**
     * @before
     */
    protected function registerAutoloadFunction(): void {
        spl_autoload_register([ClassLoader::class, 'loadClass'], true, false);
    }

    /**
     * @after
     */
    protected function unRegisterAutoloadFunction(): void {
        spl_autoload_unregister([ClassLoader::class, 'loadClass']);
    }
}
