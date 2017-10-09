<?php
namespace PHPKitchen\DI;

use PHPKitchen\DI\Contracts\ContainerAware;
use PHPKitchen\DI\Mixins\ContainerAccess;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Represents a factory for any class.
 *
 * Example:
 * <pre>
 *  $application = \Yii::$app;
 *  $factory = new ClassFactory();
 *  $factory->setClassName(MyAwesomeService::class);
 *  $factory->setDefaultConstructorParams([
 *      $application->db,
 *      $application->security,
 *  ]);
 *  $factory->setDefaultConfig([
 *      'myAwesomeProperty' => 25,
 *  ]);
 *  // Simple usage of factory. Instantiation of an object with default configuration and default constructor params
 *  $simpleService = $factory->create();
 *
 *  // Instantiation of an object with custom configuration that will be merged wit default
 *  $configuredService = $factory->create(['myAnotherAwesomeProperty' => 31]);
 *
 *  // Instantiation of an object with custom constructor params. Note: params  won't be merged wit default
 *  $serviceWithCustomConstructoParams = $factory->create([
 *      new \yii\db\Connection([
 *          // custom DB connection config
 *      ]),
 *      $application->security,
 *  ]);
 * </pre>
 *
 * @property string $className public alias of {@link _className}
 * @property array $defaultConfig public alias of {@link _defaultConfig}
 * @property array $defaultConstructorParams public alias of {@link _defaultConstructorParams}
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
class ClassFactory extends Component implements ContainerAware {
    use ContainerAccess;
    /**
     * @var string a class factory should instantiate.
     */
    protected $_className;
    /**
     * @var array a configuration array of the name-value pairs that will be used to initialize
     * the corresponding object default properties. This config may be overridden by configuration
     * passed to specific methods.
     */
    protected $_defaultConfig = [];
    /**
     * @var array default constructor parameters. If not specified container will handle constructor params instantiation if needed.
     * This config may be overridden by params configuration passed to {@link createWithConstructorParams}
     */
    protected $_defaultConstructorParams = [];

    /**
     * Creates a new object using the given configuration.
     *
     * @param array $config a configuration array of the name-value pairs that will be used to initialize
     * the corresponding object properties
     *
     * @return object the created object
     * @throws \yii\base\InvalidConfigException if the configuration is invalid.
     * @see \PHPKitchen\DI\Container
     */
    public function create(array $config = []) {
        $container = $this->getContainer();
        return $container->create($this->prepareObjectDefinitionFromConfig($config), $this->getDefaultConstructorParams());
    }

    /**
     * Creates a new object with specified constructor parameters using the given or default configuration.
     *
     * @param array $params the constructor parameters
     * @param array $config a configuration array of the name-value pairs that will be used to initialize
     * the corresponding object properties
     *
     * @return object the created object
     * @throws \yii\base\InvalidConfigException if the configuration is invalid.
     * @see \PHPKitchen\DI\Container
     */
    public function createWithConstructorParams(array $params, $config = []) {
        $definition = $this->prepareObjectDefinitionFromConfig($config);
        return $this->getContainer()->create($definition, $params);
    }

    protected function prepareObjectDefinitionFromConfig($config) {
        $definition = $this->getDefaultConfig();
        $definition = ArrayHelper::merge($definition, $config);
        $definition['class'] = $this->getClassName();
        return $definition;
    }

    // GETTERS/SETTERS

    public function getClassName() {
        return $this->_className;
    }

    public function setClassName($className) {
        $this->_className = $className;
    }

    public function getDefaultConfig() {
        return $this->_defaultConfig;
    }

    public function setDefaultConfig(array $defaultConfig) {
        $this->_defaultConfig = $defaultConfig;
    }

    public function getDefaultConstructorParams() {
        return $this->_defaultConstructorParams;
    }

    public function setDefaultConstructorParams(array $defaultConstructorArguments) {
        $this->_defaultConstructorParams = $defaultConstructorArguments;
    }
}