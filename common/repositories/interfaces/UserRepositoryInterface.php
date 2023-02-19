<?php

declare(strict_types=1);

namespace common\repositories\interfaces;

interface UserRepositoryInterface
{
    function existsByUsername(string $username);
}
