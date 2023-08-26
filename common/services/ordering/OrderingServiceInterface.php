<?php

declare(strict_types=1);

namespace common\services\ordering;

interface OrderingServiceInterface
{
    /**
     * @param int $ordering
     * @return void
     */
    public function indexAfterNewCurrentOrdering(int $ordering): void;

    /**
     * @param int $newOrdering
     * @return void
     */
    public function setOrdering(int $newOrdering): void;
}