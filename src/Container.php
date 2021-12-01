<?php

namespace PHPKitchen\DI;

use PHPKitchen\DI\Contracts\ServiceProvider;
use SplObjectStorage;
use yii\base\InvalidConfigException;

/**
 * Represents advanced Dependency Injection container.
 *
 * Provides such features as:
 * - decorators: allows decorating objects without affecting their container definitions.
 * - service providers: allows grouping complex dependencies definitions in a single class that bootstraps
 * service or services.
 * - factories: generate factories for classes dynamically.
 *
 * @package core\di
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class Container extends \yii\di\Container implements Contracts\Container {
    /**
     * @var Contracts\ObjectDecorator[]|array
     */
    protected array $_decorators;
    /**
     * @var Contracts\DelayedServiceProvider[]|SplObjectStorage
     */
    protected $delayedServiceProviders;
    /**
     * @var string default class for factory.
     */
    public string $factoryClass = ClassFactory::class;

    public function init(): void {
        parent::init();
        $this->delayedServiceProviders = new SplObjectStorage();
    }

    /**
     * @throws InvalidConfigException
     */
    public function addProvider($provider): void {
        $this->registerServiceProvider($provider);
    }

    public function addDecorator($objectName, $decorator): void {
        $this->_decorators[$objectName][] = $decorator;
    }

    /**
     * @throws InvalidConfigException
     */
    public function createFactoryFor(string $class): object {
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
     *
     * @return object|string the created object
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

    protected function registerDelayedServiceProviderFor($classOrInterface): void {
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
     *
     * @return object the object itself
     */
    public function configureObject(object $object, array $properties): object {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }

        return $object;
    }

    /**
     * @param string $definitionName class or name of definition in container
     *
     * @return array definition or empty array if definition not set.
     */
    public function getDefinitionOf(string $definitionName): array {
        $definitions = $this->getDefinitions();

        return $definitions[$definitionName] ?? [];
    }

    /**
     * @throws InvalidConfigException
     */
    protected function runDecoratorsOnObject($decoratorsGroupName, $object): void {
        if (!$this->isDecoratorsGroupRegistered($decoratorsGroupName)) {
            return;
        }
        foreach ($this->_decorators[$decoratorsGroupName] as &$decorator) {
            if (is_callable($decorator)) {
                $decorator($object);
                continue;
            }
            if (!is_object($decorator)) {
                $decorator = $this->create($decorator);
            }
            $decorator->decorate($object);
        }
    }

    protected function isDecoratorsGroupRegistered($decoratorsGroup): bool {
        $objectName = is_object($decoratorsGroup) ? get_class($decoratorsGroup) : $decoratorsGroup;

        return isset($this->_decorators[$objectName]) && !empty($this->_decorators[$objectName]);
    }

    /**
     * @param ServiceProvider|array $serviceProvider
     *
     * @throws InvalidConfigException
     */
    protected function registerServiceProvider($serviceProvider): void {
        $serviceProvider = $this->ensureProviderIsObject($serviceProvider);

        if (!($serviceProvider instanceof Contracts\ServiceProvider)) {
            throw new InvalidConfigException('Service provider should be an instance of ' . ServiceProvider::class);
        } elseif ($serviceProvider instanceof Contracts\DelayedServiceProvider) {
            $this->addDelayedServiceProvider($serviceProvider);
        } else {
            $serviceProvider->register();
        }
    }

    protected function addDelayedServiceProvider($provider): void {
        $this->delayedServiceProviders->attach($provider);
    }

    /**
     * @throws InvalidConfigException
     */
    protected function ensureProviderIsObject($provider): ServiceProvider {
        if (!is_object($provider)) {
            $provider = $this->create($provider);
        }

        return $provider;
    }

    //region ---------------------- SETTERS -------------------------------

    public function setDecorators(string $name, array $decorators): void {
        foreach ($decorators as $decorator) {
            $this->addDecorator($name, $decorator);
        }
    }

    /**
     * @throws InvalidConfigException
     */
    public function setServiceProviders(array $serviceProviders): void {
        foreach ($serviceProviders as $serviceProvider) {
            $this->registerServiceProvider($serviceProvider);
        }
    }
    //endregion
}
