<?php

declare(strict_types=1);
// PROD INDEX FILE
require __DIR__ . '/../../vendor/autoload.php';

$local = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/local.php',
    require __DIR__ . '/../config/local.php'
);

defined('YII_DEBUG') or define('YII_DEBUG', $local['yii_debug']);
defined('YII_ENV') or define('YII_ENV', $local['yii_env']);

require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/config.php',
    require __DIR__ . '/../config/config.php',
);

(new yii\web\Application($config))->run();
