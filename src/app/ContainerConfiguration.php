<?php
namespace dekey\di\app;

use dekey\di\Container;
use yii\base\InvalidConfigException;

/**
 * Represents helper functionality that allow to configure container through application
 * configuration.
 *
 * To configure container through application you need to specify "container" configuration array
 * in your configuration file. For example:
 *       [
 *          'container' => [
 *              'components' => [
 *                  // basic component's definitions, e.g. $container->set($serviceName, $definition)
 *              ],
 *              'decorators' => [
 *                  // shortcat for service decorators, e.g. $container->addDecorator($serviceName, $decorator)
 *              ],
 *              'serviceProviders' => [
 *                  // shortcat for service providers, e.g. $container->registerServiceProvider($definition)
 *              ],
 *              'singletons' => [
 *                  // shortcat for singletons, e.g. $container->setSingletons($singletonName, $definition)
 *             ],
 *          ],
 *          'components' => [
 *               // usual components configuration
 *          ]
 *      ],
 *
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
trait ContainerConfiguration {
    protected $_containerConfig;

    public function __construct($config = []) {
        if (isset($config['container'])) {
            $this->_containerConfig = $config['container'];
            unset($config['container']);
        }
        parent::__construct($config);
        $this->configureContainer();

    }

    public function configureContainer() {
        $containerConfig = $this->_containerConfig;
        if (!$containerConfig) {
            return;
        } elseif (!is_a(\Yii::$container, Container::class)) {
            throw new InvalidConfigException('Container should be an instance of ' . Container::class);
        }
        /**
         * @var Container $container
         */
        $container = \Yii::$container;
        $container->configureObject($container, $containerConfig);
    }
}