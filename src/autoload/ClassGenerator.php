<?php

namespace dekey\di\autoload;

use Yii;

/**
 * Represents code generator that can generate virtual class definition of IoC container.
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassGenerator {
    /**
     * @var \dekey\di\Container
     */
    private $container;
    /**
     * @var \yii\base\Application
     */
    private $application;

    public function __construct() {
        $this->container = Yii::$container;
        $this->application = Yii::$app;
    }

    public function getClassFileNameIfExistOrGenerate($class) {
        if ($this->isClassGenerated($class) || ($this->canClassBeGenerated($class) && $this->generateClassFileIfNotExist($class))) {
            $fileName = $this->buildClassFileName($class);
        } else {
            $fileName = false;
        }

        return $fileName;
    }

    protected function canClassBeGenerated($class) {
        return $this->extractBaseClassFromDefinitionOf($class) !== false;
    }

    public function generateClassFileIfNotExist($class) {
        if ($this->isClassNotGenerated($class)) {
            $baseClassName = $this->extractBaseClassFromDefinitionOf($class);
            $isClassGenerated = $baseClassName && $this->tryToGenerateClass($class, $baseClassName);
        } else {
            $isClassGenerated = false;
        }

        return $isClassGenerated;
    }

    public function isClassNotGenerated($class) {
        return !$this->isClassGenerated($class);
    }

    public function isClassGenerated($class) {
        $classFileName = $this->buildClassFileName($class);
        return file_exists($classFileName);
    }

    public function buildClassFileName($class) {
        $baseClassName = $this->extractBaseClassFromDefinitionOf($class);
        $runtimePath = $this->application->runtimePath;
        $fullClassName = str_replace('\\', '_', "{$class}__{$baseClassName}");
        return "{$runtimePath}/{$fullClassName}.php";
    }

    protected function extractBaseClassFromDefinitionOf($class) {
        $definition = $this->container->getDefinitionOf($class);
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

    protected function checkWhetherClassCanBeExtended($class) {
        try {
            $baseClassReflection = new \ReflectionClass($class);
            $classCanBeExtended = !($baseClassReflection->isFinal() || $baseClassReflection->isInterface());
        } catch (\Exception $e) {
            $classCanBeExtended = false;
        }
        return $classCanBeExtended;
    }

    protected function generateClassFromTemplate($class, $baseClass) {
        $templateParams = $this->prepareTemplateParams($class, $baseClass);
        $classContent = $this->renderTemplate($templateParams);

        return file_put_contents($this->buildClassFileName($class), $classContent);
    }

    protected function prepareTemplateParams($class, $baseClass) {
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
}