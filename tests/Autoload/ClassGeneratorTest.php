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
    public function testFileGeneratedOrNot() {
        $tester = $this->tester;
        $tester->checksScenario('generator determine whether class files exists for virtual classes or not');

        $generator = $this->createGenerator();

        $isClassGenerated = $generator->isClassGenerated(self::EXISTING_VIRTUAL_CLASS);
        $tester->expectsThat('generator can find existing class file in runtime directory')
               ->boolean($isClassGenerated)
               ->isTrue();

        $isClassGenerated = $generator->isClassNotGenerated(self::NOT_EXISTING_VIRTUAL_CLASS);
        $tester->expectsThat('generator detect that class file do not exists in runtime directory')
               ->boolean($isClassGenerated)
               ->isTrue();
    }

    /**
     * @covers ::generateClassFileIfNotExist
     * @covers ::<protected>
     */
    public function testGenerateClass() {
        $notExistingVirtualClassName = self::NOT_EXISTING_VIRTUAL_CLASS;
        $this->container->set($notExistingVirtualClassName, ConfigurableClass::class);
        $generator = $this->createGenerator();

        $generator->generateClassFileIfNotExist($notExistingVirtualClassName);

        $tester = $this->tester;
        $tester->checksScenario('generator generates class file for virtual class based on container definition');
        $tester->expectsThat('generator successfully generated virtual class file from template to runtime directory')
               ->file(self::NOT_EXISTING_VIRTUAL_CLASS_FILE_NAME)
               ->isExist();

        $tester->expectsThat('generated file contains valid class definition')
               ->file(self::NOT_EXISTING_VIRTUAL_CLASS_FILE_NAME)
               ->isEqualTo(__DIR__ . '/extected-virtual-class-file-content.php');
    }
}