<?php

declare(strict_types=1);

use yii\helpers\ArrayHelper;

$local = require __DIR__ . '/local.php';

$env = YII_ENV;

return ArrayHelper::merge(
    require __DIR__ . "/common/config.php",
    require __DIR__ . "/$env/config.php"
);
