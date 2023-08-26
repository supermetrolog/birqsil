<?php

declare(strict_types=1);

namespace common\factories;

use common\services\ordering\OrderingService;
use common\services\ordering\OrderingServiceInterface;
use yii\db\Connection;

class OrderingServiceFactory
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @param OrderingServiceInterface $service
     * @return OrderingService
     */
    public function create(OrderingServiceInterface $service): OrderingService
    {
        return new OrderingService($service, $this->connection);
    }
}