<?php

declare(strict_types=1);

use yii\helpers\ArrayHelper;

$local = ArrayHelper::merge(
    require __DIR__ . '/../../common/config/local.php',
    require __DIR__ . '/local.php'
);

return ArrayHelper::merge(
    require __DIR__ . '/../../common/config/config.php',
    require __DIR__ . '/config.php'
);
