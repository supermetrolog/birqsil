<?php

declare(strict_types=1);

use yii\helpers\ArrayHelper;

$local = require __DIR__ . '/local.php';

return ArrayHelper::merge(
    require __DIR__ . "/common/config.php",
    require __DIR__ . "/test/config.php"
);
