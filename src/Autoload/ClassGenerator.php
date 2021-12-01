<?php

namespace PHPKitchen\DI\Autoload;

use Exception;
use PHPKitchen\DI\Container;
use ReflectionClass;
use Yii;

/**
 * Represents code generator that can generate virtual class definition of IoC container.
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassGenerator {
    public function getClassFileNameIfExistOrGenerate($class) {
        if ($this->isClassGenerated($class) || ($this->canClassBeGenerated($class) && $this->generateClassFileIfNotExist($class))) {
            $fileName = $this->buildClassFileName($class);
        } else {
            $fileName = false;
        }

        return $fileName;
    }

    protected function canClassBeGenerated($class): bool {
        return $this->extractBaseClassFromDefinitionOf($class) !== false;
    }

    public function generateClassFileIfNotExist($class): bool {
        if ($this->isClassNotGenerated($class)) {
            $baseClassName = $this->extractBaseClassFromDefinitionOf($class);
            $isClassGenerated = $baseClassName && $this->tryToGenerateClass($class, $baseClassName);
        } else {
            $isClassGenerated = false;
        }

        return $isClassGenerated;
    }

    public function isClassNotGenerated($class): bool {
        return !$this->isClassGenerated($class);
    }

    public function isClassGenerated($class): bool {
        $classFileName = $this->buildClassFileName($class);

        return file_exists($classFileName);
    }

    public function buildClassFileName($class): string {
        $baseClassName = $this->extractBaseClassFromDefinitionOf($class);
        $application = $this->getApplication();
        $runtimePath = $application->runtimePath ?? sys_get_temp_dir();
        $fullClassName = str_replace('\\', '_', "{$class}__{$baseClassName}");

        return "{$runtimePath}/{$fullClassName}.php";
    }

    protected function extractBaseClassFromDefinitionOf($class) {
        $container = $this->getContainer();
        if (!$container) {
            return false;
        }
        $definition = $container->getDefinitionOf($class);
        if (is_array($definition) && isset($definition['class'])) {
            $baseClassName = $definition['class'];
        } elseif (is_string($definition)) {
            $baseClassName = $definition;
        } else {
            $baseClassName = false;
        }

        return $baseClassName;
    }

    protected function tryToGenerateClass($class, $baseClass) {
        $classCanBeExtended = $this->checkWhetherClassCanBeExtended($baseClass);

        return $classCanBeExtended ? $this->generateClassFromTemplate($class, $baseClass) : false;
    }

    protected function checkWhetherClassCanBeExtended($class): bool {
        try {
            $baseClassReflection = new ReflectionClass($class);
            $classCanBeExtended = !($baseClassReflection->isFinal() || $baseClassReflection->isInterface());
        } catch (Exception $e) {
            $classCanBeExtended = false;
        }

        return $classCanBeExtended;
    }

    protected function generateClassFromTemplate($class, $baseClass) {
        $templateParams = $this->prepareTemplateParams($class, $baseClass);
        $classContent = $this->renderTemplate($templateParams);

        return file_put_contents($this->buildClassFileName($class), $classContent);
    }

    protected function prepareTemplateParams($class, $baseClass): array {
        $delimiterBeforeClassNamePosition = strrpos($class, '\\');
        if ($delimiterBeforeClassNamePosition !== false) {
            $namespaceName = substr($class, 0, $delimiterBeforeClassNamePosition);
            $className = substr($class, $delimiterBeforeClassNamePosition + 1);
        } else {
            $namespaceName = false;
            $className = $class;
        }

        return compact('className', 'namespaceName', 'baseClass');
    }

    protected function renderTemplate($params) {
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        require __DIR__ . '/class-template.php';

        return ob_get_clean();
    }

    /**
     * @return Container
     */
    protected function getContainer() {
        return Yii::$container;
    }

    protected function getApplication() {
        return Yii::$app;
    }
}
