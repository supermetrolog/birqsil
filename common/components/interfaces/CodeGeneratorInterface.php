<?php

declare(strict_types=1);

namespace common\components\interfaces;

interface CodeGeneratorInterface
{
    function generate(int $min, int $max): int;
}
