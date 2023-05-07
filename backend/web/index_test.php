<?php
declare(strict_types=1);

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}


require __DIR__ . '/../../vendor/autoload.php';

$local = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/local.php',
    require __DIR__ . '/../config/local.php'
);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/codeception-local.php',
    require __DIR__ . '/../config/codeception-local.php',
);

(new yii\web\Application($config))->run();

