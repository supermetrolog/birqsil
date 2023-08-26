<?php

declare(strict_types=1);

namespace common\services;

use common\base\exception\SaveModelException;
use common\models\AR\MenuItem;
use common\repositories\MenuItemRepository;

class MenuItemOrderingService implements ordering\OrderingServiceInterface
{
    public function __construct(
        private readonly MenuItemRepository $repository,
        private readonly MenuItem $model
    )
    {
    }

    /**
     * @param int $ordering
     * @return void
     */
    public function indexAfterNewCurrentOrdering(int $ordering): void
    {
        $this->repository->indexAfterNewCurrentOrdering($ordering);
    }

    /**
     * @param int $newOrdering
     * @return void
     * @throws SaveModelException
     */
    public function setOrdering(int $newOrdering): void
    {
        $this->model->ordering = $newOrdering;
        $this->model->saveOrThrow();
    }

    /**
     * @return int
     */
    public function getLastOrdering(): int
    {
        return $this->repository->getLastOrdering() ?? 0;
    }
}