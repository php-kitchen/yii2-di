# Yii2 Container

Advanced Dependency Injection Container fo Yii 2. 


## Package information

Latest Stable Version | Total downloads | Monthly Downloads | Licensing 
--------------------- |  -------------- | ----------------  | --------- 
[![Latest Stable Version](https://poser.pugx.org/dekeysoft/yii2-container/v/stable)](https://packagist.org/packages/dekeysoft/yii2-container)| [![Total Downloads](https://poser.pugx.org/dekeysoft/yii2-container/downloads)](https://packagist.org/packages/dekeysoft/yii2-container) | [![Monthly Downloads](https://poser.pugx.org/dekeysoft/yii2-container/d/monthly)](https://packagist.org/packages/dekeysoft/yii2-container) | [![License](https://poser.pugx.org/dekeysoft/yii2-container/license)](https://packagist.org/packages/dekeysoft/yii2-container)


## Requirements

**`PHP >= 5.6.0` is required.**

## Getting Started

Run the following command to add Yii2 Container to your project's `composer.json`. See [Packagist](https://packagist.org/packages/dekeysoft/yii2-container) for specific versions.

```bash
composer require dekeysoft/yii2-container
```

Or you can copy this library from:
- [Packagist](https://packagist.org/packages/dekeysoft/yii2-container)
- [Github](https://github.com/dekeysoft/yii2-container)

Then add following line of code in you application entry point or bootstrap file:
```php
Yii::$container = new \dekey\di\Container();
spl_autoload_unregister([\dekey\di\autoload\ClassLoader::class, 'loadClass']);
```

For additional information and guides go to the [project documentation](docs/README.md)

## Build status

CI status    | Code quality
------------ | ------------
[![Build Status](https://travis-ci.org/dekeysoft/yii2-container.svg?branch=master)](https://travis-ci.org/dekeysoft/yii2-container) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dekeysoft/yii2-container/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dekeysoft/yii2-container/?branch=master)