<?php

namespace PHPKitchen\DI\Contracts;

/**
 * Represents a component responsible for services registration in DI container.
 * The goal of service providers is to centralize and organize in one place
 * registration of services bound by any logic or services with complex dependencies.
 * For example, you can have a service that requires several dependencies and those dependencies
 * also have their dependencies. You can simply organize registration of service, and it's dependencies
 * in a single provider class except creating bootstrap file or configuration array for container. Pseudocode might look
 * like:
 * <pre>
 * class MyServiceProvider implements ServiceProvider {
 *    public function register() {
 *        $this->registerDependencies();
 *        $this->registerService();
 *    }
 *
 *    protected function registerDependencies() {
 *        $container = $this->container;
 *        $container->set('dependency1', SomeClass1::class);
 *        $container->set('dependency2', SomeClass2::class);
 *        $container->set('dependency3', [
 *            'class' => SomeClass3::class,
 *            'dependency' => $container->get('dependency1')
 *        ]);
 *        $container->set('dependency4', [
 *            'class' => SomeClass4::class,
 *            'dependency' => $container->get('dependency2')
 *        ]);
 *    }
 *
 *    protected function registerService() {
 *        $this->container->set('myService', function(DiContainer $container) {
 *            return $container->create([
 *                'class' => MyService::class,
 *                'dependency' => $container->get('dependency3'),
 *                'dependency2' => $container->get('dependency4'),
 *            ]);
 *        });
 *    }
 * }
 * </pre>
 *
 *
 * @package PHPKitchen\DI\contracts
 * @author Dmitry Kolodko <prowwid@gmail.com>
 */
interface ServiceProvider {
    public function register();
}
