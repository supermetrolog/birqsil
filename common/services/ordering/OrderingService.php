<?php

declare(strict_types=1);

namespace common\services\ordering;

use Throwable;
use yii\db\Connection;

class OrderingService
{
    public function __construct(
        private readonly OrderingServiceInterface $service,
        private readonly Connection $connection
    )
    {
    }

    /**
     * @param int|null $afterOrdering
     * @return void
     * @throws Throwable
     */
    public function order(int|null $afterOrdering): void
    {
        $tx = $this->connection->beginTransaction();

        try {
            if ($afterOrdering) {
                $newOrdering = $afterOrdering;
            } else {
                $newOrdering = $this->service->getLastOrdering() + 1;
            }


            $this->service->indexAfterNewCurrentOrdering($newOrdering);

            $this->service->setOrdering($newOrdering);

            $tx->commit();
        } catch (Throwable $th) {
            $tx->rollBack();
            throw $th;
        }
    }
}