<?php

namespace dekey\di;

use dekey\di\contracts\ServiceProvider;
use yii\base\InvalidConfigException;

/**
 * Represents advanced Dependency Injection container.
 *
 * Provides such features as:
 * - decorators: allows to decorate objects without affecting their container definitions.
 * - service providers: allows to group complex dependencies definitions in a single class that bootstraps
 * service or services.
 * - factories: generate factories for classes dynamically.
 *
 * @package core\di
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class Container extends \yii\di\Container implements contracts\Container {
    /**
     * @var contracts\ObjectDecorator[]|array
     */
    protected $_decorators;
    /**
     * @var contracts\ServiceProvider[]|array
     */
    protected $_serviceProviders;
    /**
     * @var contracts\ServiceProvider[]|array
     */
    protected $_delayedServiceProviders;
    public $factoryClass = ClassFactory::class;

    public function createFactoryFor($class) {
        return $this->create([
            'class' => $this->factoryClass,
            'className' => $class,
        ]);
    }

    /**
     * Creates a new object using the given configuration.
     *
     * @param string|array|callable $type the object type. This can be specified in one of the following forms:
     *
     * - a string: representing the class name of the object to be created
     * - a configuration array: the array must contain a `class` element which is treated as the object class,
     *   and the rest of the name-value pairs will be used to initialize the corresponding object properties
     * - a PHP callable: either an anonymous function or an array representing a class method (`[$class or $object, $method]`).
     *   The callable should return a new instance of the object being created.
     *
     * @param array $params the constructor parameters
     * @return object the created object
     * @throws InvalidConfigException if the configuration is invalid.
     * @see \yii\di\Container
     */
    public function create($type, array $params = []) {
        if (is_string($type)) {
            $object = $this->get($type, $params);
        } elseif (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            unset($type['class']);
            $object = $this->get($class, $params, $type);
        } elseif (is_callable($type, true)) {
            $object = $this->invoke($type, $params);
        } elseif (is_array($type)) {
            throw new InvalidConfigException('Object configuration must be an array containing a "class" element.');
        } else {
            throw new InvalidConfigException('Unsupported configuration type: ' . gettype($type));
        }

        return $object;
    }

    /**
     * @inheritdoc
     * @override
     */
    public function get($class, $params = [], $config = []) {
        try {
            $object = parent::get($class, $params, $config);
        } catch (\Exception $e) {
            $this->registerDelayedServiceProviders();
            $object = parent::get($class, $params, $config);
        }

        $this->runDecoratorsOnObject($class, $object);

        return $object;
    }

    protected function registerDelayedServiceProviders() {
        if (empty($this->_delayedServiceProviders)) {
            return;
        }
        foreach ($this->_delayedServiceProviders as $delayedServiceProvider) {
            $this->registerServiceProvider($delayedServiceProvider);
        }
    }

    /**
     * Configures an object with the initial property values.
     *
     * @param object $object the object to be configured
     * @param array $properties the property initial values given in terms of name-value pairs.
     * @return object the object itself
     */
    public function configureObject($object, $properties) {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }

        return $object;
    }

    /**
     * @param string $definitionName class or name of definition in container
     * @return array definition or empty array if definition not set.
     */
    public function getDefinitionOf($definitionName) {
        $definitions = $this->getDefinitions();
        return isset($definitions[$definitionName]) ? $definitions[$definitionName] : [];
    }

    public function addProvider($provider) {
        if (!is_object($provider)) {
            $provider = $this->create($provider);
        }
        $provider->register();
    }

    protected function runDecoratorsOnObject($decoratorsGroupName, $object) {
        if (!$this->isDecoratorsGroupRegistered($decoratorsGroupName)) {
            return;
        }
        foreach ($this->_decorators[$decoratorsGroupName] as &$decorator) {
            if (is_callable($decorator)) {
                call_user_func($decorator, $object);
                continue;
            }
            if (!is_object($decorator)) {
                $decorator = $this->create($decorator);
            }
            $decorator->decorate($object);
        }
    }

    public function addDecorator($objectName, $decorator) {
        $this->_decorators[$objectName][] = $decorator;
    }

    protected function isDecoratorsGroupRegistered($decoratorsGroup) {
        $objectName = is_object($decoratorsGroup) ? get_class($decoratorsGroup) : $decoratorsGroup;
        return isset($this->_decorators[$objectName]) && !empty($this->_decorators[$objectName]);
    }

    public function setDecorators($name, array $decorators) {
        foreach ($decorators as $decorator) {
            $this->addDecorator($name, $decorator);
        }
    }

    public function setServiceProviders(array $serviceProviders) {
        foreach ($serviceProviders as $serviceProvider) {
            $this->registerServiceProvider($serviceProvider);
        }
    }

    protected function registerServiceProvider($serviceProvider) {
        if (!is_object($serviceProvider)) {
            $serviceProvider = $this->create($serviceProvider);
        }
        if (!($serviceProvider instanceof ServiceProvider)) {
            throw new InvalidConfigException('Service provider should be an instance of ' . ServiceProvider::class);
        } elseif ($serviceProvider->shouldBeDelayed()) {
            $this->_delayedServiceProviders[] = $serviceProvider;
        } else {
            $serviceProvider->register();
        }
    }

    public function setSingletons(array $singletons) {
        $componentConfigurations = $this->expandComponentsConfig($singletons);
        foreach ($componentConfigurations as $config) {
            list ($class, $definition, $params) = $config;
            $this->setSingleton($class, $definition, $params);
        }
    }

    public function setComponents(array $components) {
        $componentsConfigurations = $this->expandComponentsConfig($components);
        foreach ($componentsConfigurations as $config) {
            list ($class, $definition, $params) = $config;
            $this->set($class, $definition, $params);
        }
    }

    protected function expandComponentsConfig(&$componentConfig) {
        foreach ($componentConfig as $name => $config) {
            $definition = [];
            $params = [];
            if (is_numeric($name) && is_string($config)) {
                $class = $config;
            } elseif (is_string($name) && is_array($config)) {
                $class = $name;
                $definition = isset($config['definition']) ? $config['definition'] : $definition;
                $params = isset($config['constructorParams']) ? $config['constructorParams'] : $params;
            } else {
                throw new InvalidConfigException('Container component configuration should be a string or combination of string key and array that contains definition and constructor params!');
            }
            yield [$class, $definition, $params];
        }
    }
}