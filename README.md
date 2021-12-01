# Yii2 Container

Advanced Dependency Injection Container fo Yii 2. 


## Package information

Latest Stable Version | Total downloads | Monthly Downloads | Licensing 
--------------------- |  -------------- | ----------------  | --------- 
[![Latest Stable Version](https://poser.pugx.org/php-kitchen/yii2-di/v/stable)](https://packagist.org/packages/php-kitchen/yii2-di)| [![Total Downloads](https://poser.pugx.org/php-kitchen/yii2-di/downloads)](https://packagist.org/packages/php-kitchen/yii2-di) | [![Monthly Downloads](https://poser.pugx.org/php-kitchen/yii2-di/d/monthly)](https://packagist.org/packages/php-kitchen/yii2-di) | [![License](https://poser.pugx.org/php-kitchen/yii2-di/license)](https://packagist.org/packages/php-kitchen/yii2-di)


## Requirements

**`PHP >=7.4` is required.**

## Getting Started

Run the following command to add Yii2 Container to your project's `composer.json`. See [Packagist](https://packagist.org/packages/php-kitchen/yii2-di) for specific versions.

```bash
composer require php-kitchen/yii2-di
```

Or you can copy this library from:
- [Packagist](https://packagist.org/packages/php-kitchen/yii2-di)
- [Github](https://github.com/php-kitchen/yii2-di)

Then add following line of code in you application entry point or bootstrap file:
```php
Yii::$container = new \PHPKitchen\DI\Container();
spl_autoload_unregister([\PHPKitchen\DI\autoload\ClassLoader::class, 'loadClass']);
```

For additional information and guides go to the [project documentation](docs/README.md)

## Upgrade from 0.0.9
In 0.1.0 `yii\base\Object` changed to `yii\base\BaseObject` to support PHP 7.2 but it means that you need yii `2.0.13` and higher. 

## Contributing

If you want to ask any questions, suggest improvements or just to talk with community and developers, [join our server at Discord](https://discord.gg/Ez5VZhC) 

## Build status

CI status    | Code quality
------------ | ------------
[![Build Status](https://travis-ci.org/php-kitchen/yii2-di.svg?branch=master)](https://travis-ci.org/php-kitchen/yii2-di) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-kitchen/yii2-di/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/php-kitchen/yii2-di/?branch=master)
