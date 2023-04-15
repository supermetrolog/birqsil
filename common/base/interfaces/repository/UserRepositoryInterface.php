<?php

declare(strict_types=1);

namespace common\base\interfaces\repository;

interface UserRepositoryInterface
{
    function existsByUsername(string $username);
}
