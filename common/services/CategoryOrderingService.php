<?php

declare(strict_types=1);

namespace common\services;

use common\base\exception\SaveModelException;
use common\models\AR\Category;
use common\repositories\CategoryRepository;

class CategoryOrderingService implements ordering\OrderingServiceInterface
{
    public function __construct(
        private readonly CategoryRepository $repository,
        private readonly Category $model
    )
    {
    }

    /**
     * @param int $ordering
     * @return void
     */
    public function indexAfterNewCurrentOrdering(int $ordering): void
    {
        $this->repository->indexAfterNewCurrentOrdering($ordering, $this->model->restaurant_id);
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