<?php

namespace PHPKitchen\DI\Tests\Autoload;

use PHPKitchen\DI\Tests\Base\VirtualClassGenerationTest;
use PHPKitchen\DI\Tests\Stubs\ConfigurableClass;

/**
 * Unit test for {@link ClassGenerator}
 *
 * @coversDefaultClass \PHPKitchen\DI\autoload\ClassGenerator
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassGeneratorTest extends VirtualClassGenerationTest {
    /**
     * @covers ::isClassGenerated
     * @covers ::isClassNotGenerated
     * @covers ::buildClassFileName
     * @covers ::extractBaseClassFromDefinitionOf
     */
    public function testFileGeneratedOrNot(): void {
        $tester = $this->tester;
        $tester->describe('generator determine whether class files exists for virtual classes or not');

        $generator = $this->createGenerator();

        $isClassGenerated = $generator->isClassGenerated(self::EXISTING_VIRTUAL_CLASS);
        $tester->expectThat('generator can find existing class file in runtime directory')
               ->seeBool($isClassGenerated)
               ->isTrue();

        $isClassGenerated = $generator->isClassNotGenerated(self::NOT_EXISTING_VIRTUAL_CLASS);
        $tester->expectThat('generator detect that class file do not exists in runtime directory')
               ->seeBool($isClassGenerated)
               ->isTrue();
    }

    /**
     * @covers ::generateClassFileIfNotExist
     * @covers ::<protected>
     */
    public function testGenerateClass(): void {
        $notExistingVirtualClassName = self::NOT_EXISTING_VIRTUAL_CLASS;
        $this->container->set($notExistingVirtualClassName, ConfigurableClass::class);
        $generator = $this->createGenerator();

        $generator->generateClassFileIfNotExist($notExistingVirtualClassName);

        $tester = $this->tester;
        $tester->describe('generator generates class file for virtual class based on container definition');
        $tester->expectThat('generator successfully generated virtual class file from template to runtime directory')
               ->seeFile(self::NOT_EXISTING_VIRTUAL_CLASS_FILE_NAME)
               ->isExist();

        $tester->expectThat('generated file contains valid class definition')
               ->seeFile(self::NOT_EXISTING_VIRTUAL_CLASS_FILE_NAME)
               ->isEqualTo(__DIR__ . '/extected-virtual-class-file-content.php');
    }
}
