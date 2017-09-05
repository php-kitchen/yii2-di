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
     * @var contracts\DelayedServiceProvider[]|\SplObjectStorage
     */
    protected $delayedServiceProviders;
    /**
     * @var string default class for factory.
     */
    public $factoryClass = ClassFactory::class;

    public function init() {
        parent::init();
        $this->delayedServiceProviders = new \SplObjectStorage();
    }

    public function addProvider($provider) {
        $this->registerServiceProvider($provider);
    }

    public function addDecorator($objectName, $decorator) {
        $this->_decorators[$objectName][] = $decorator;
    }

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
        $this->registerDelayedServiceProviderFor($class);

        $object = parent::get($class, $params, $config);

        $this->runDecoratorsOnObject($class, $object);

        return $object;
    }

    protected function registerDelayedServiceProviderFor($classOrInterface) {
        $delayedServiceProviders = $this->delayedServiceProviders;
        if ($delayedServiceProviders->count() === 0) {
            return;
        }

        foreach ($delayedServiceProviders as $delayedServiceProvider) {
            if ($delayedServiceProvider->provides($classOrInterface)) {
                $delayedServiceProvider->register();
                $delayedServiceProviders->detach($delayedServiceProvider);
            }
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

    protected function isDecoratorsGroupRegistered($decoratorsGroup) {
        $objectName = is_object($decoratorsGroup) ? get_class($decoratorsGroup) : $decoratorsGroup;
        return isset($this->_decorators[$objectName]) && !empty($this->_decorators[$objectName]);
    }

    /**
     * @param ServiceProvider $serviceProvider
     * @throws InvalidConfigException
     */
    protected function registerServiceProvider(ServiceProvider $serviceProvider) {
        $serviceProvider = $this->ensureProviderIsObject($serviceProvider);

        if (!($serviceProvider instanceof contracts\ServiceProvider)) {
            throw new InvalidConfigException('Service provider should be an instance of ' . ServiceProvider::class);
        } elseif ($serviceProvider instanceof contracts\DelayedServiceProvider) {
            $this->addDelayedServiceProvider($serviceProvider);
        } else {
            $serviceProvider->register();
        }
    }

    protected function addDelayedServiceProvider($provider) {
        $this->delayedServiceProviders->attach($provider);
    }

    protected function ensureProviderIsObject($provider) {
        if (!is_object($provider)) {
            $provider = $this->create($provider);
        }
        return $provider;
    }

    //region ---------------------- SETTERS -------------------------------

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
    //endregion
}