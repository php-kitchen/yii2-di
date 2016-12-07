<?php
$rootDir = dirname(__DIR__);

require_once("{$rootDir}/vendor/autoload.php");
require_once("{$rootDir}/vendor/yiisoft/yii2/Yii.php");
require_once("{$rootDir}/src/utils/bootstrap.php");
error_reporting(E_ALL);
new \yii\console\Application(['id' => 'test-app', 'basePath' => "{$rootDir}/src"]);