<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

$local = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/local.php',
    require __DIR__ . '/../config/local.php'
);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/codeception-local.php',
    require __DIR__ . '/../config/codeception-local.php',
);

(new yii\web\Application($config))->run();