<?php

declare(strict_types=1);

namespace common\repositories;

use common\models\AR\Category;
use common\models\AR\MenuItem;
use yii\db\Expression;

class CategoryRepository
{
    /**
     * @param int $id
     * @param int $userId
     * @param array $with
     * @return MenuItem|null
     */
    public function findByIdAndUserId(int $id, int $userId, array $with = []): Category|null
    {
        return Category::find()->byId($id)->byUserId($userId)->with($with)->one();
    }

    /**
     * @return int|null
     */
    public function getLastOrdering(): int|null
    {
        return Category::find()->orderByOrdering()->one()?->ordering;
    }

    /**
     * @param int $ordering
     * @param int $restaurant_id
     * @return int
     */
    public function indexAfterNewCurrentOrdering(int $ordering, int $restaurant_id): int
    {
        return Category::updateAll(
            ['ordering' => new Expression('ordering + 1')],
            [
                'AND',
                ['>=', 'ordering', $ordering],
                'restaurant_id' => $restaurant_id
            ]
        );
    }
}