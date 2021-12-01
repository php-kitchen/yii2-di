<?php

namespace PHPKitchen\DI\Tests\Base;

include __DIR__ . '/../../src/Autoload/ClassLoader.php';

use PHPKitchen\DI\Autoload\ClassGenerator;
use PHPKitchen\DI\Tests\Autoload\VirtualClass;
use PHPKitchen\DI\Tests\Stubs\ConfigurableClass;

/**
 * Represents base class for autoload related tests.
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
abstract class VirtualClassGenerationTest extends TestCase {
    public const EXISTING_VIRTUAL_CLASS = VirtualClass::class;
    public const NOT_EXISTING_VIRTUAL_CLASS = 'PHPKitchen\DI\Tests\Autoload\SecondVirtualClass';
    public const NOT_EXISTING_VIRTUAL_CLASS_FILE_NAME = __DIR__ . '/../runtime/PHPKitchen_DI_Tests_Autoload_SecondVirtualClass__PHPKitchen_DI_Tests_Stubs_ConfigurableClass.php';

    protected function setUp(): void {
        parent::setUp();
        $this->setUpDefinitionsInContainer();
        $this->removeGeneratedFilesIfExists();
    }

    protected function createGenerator(): ClassGenerator {
        return new ClassGenerator();
    }

    protected function setUpDefinitionsInContainer(): void {
        $this->container->set(self::EXISTING_VIRTUAL_CLASS, [
            'class' => ConfigurableClass::class,
        ]);
        $this->container->set(self::NOT_EXISTING_VIRTUAL_CLASS, ConfigurableClass::class);
    }

    protected function removeGeneratedFilesIfExists(): void {
        $fileName = self::NOT_EXISTING_VIRTUAL_CLASS_FILE_NAME;
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    }
}
