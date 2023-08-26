<?php

declare(strict_types=1);

namespace common\repositories;

use common\models\AR\MenuItem;
use yii\db\Expression;

class MenuItemRepository
{
    /**
     * @param int $ordering
     * @return int
     */
    public function indexAfterNewCurrentOrdering(int $ordering): int
    {
        return MenuItem::updateAll(
            ['ordering' => new Expression('ordering + 1')],
            ['>=', 'ordering', $ordering]
        );
    }

    /**
     * @param int $ordering
     * @return MenuItem|null
     */
    public function getModelBeforeOrdering(int $ordering): MenuItem|null
    {
        return MenuItem::find()->orderByOrdering()->andWhere(['<', 'ordering', $ordering])->one();
    }

    /**
     * @param int $id
     * @param int $userId
     * @param array $with
     * @return MenuItem|null
     */
    public function findNotDeletedByIdAndUserId(int $id, int $userId, array $with = []): MenuItem|null
    {
        return MenuItem::find()->byId($id)->byUserId($userId)->notDeleted()->with($with)->one();
    }

    /**
     * @return int|null
     */
    public function getLastOrdering(): int|null
    {
        return MenuItem::find()->orderByOrdering()->one()?->ordering;
    }
}