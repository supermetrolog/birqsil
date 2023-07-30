<?php

namespace common\models\AQ;

use common\models\AR\Category;
use yii\db\ActiveQuery;

class CategoryQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Category[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Category|array|null
     */
    public function one($db = null): Category|array|null
    {
        $this->limit(1);
        return parent::one($db);
    }

    /**
     * @param int $id
     * @return self
     */
    public function withoutId(int $id): self
    {
        return $this->andWhere(['!=', Category::tableName() . '.id', $id]);
    }

    /**
     * @return self
     */
    public function orderByOrdering(): self
    {
        return $this->orderBy([Category::tableName() . '.ordering' => SORT_DESC]);
    }

    /**
     * @return int
     */
    public function lastOrdering(): int
    {
        return $this->orderByOrdering()->one()?->ordering ?? 0;
    }

    /**
     * @param int $id
     * @return self
     */
    public function byId(int $id): self
    {
        return $this->andWhere([Category::tableName() . '.id' => $id]);
    }

    /**
     * @param int $id
     * @return self
     */
    public function byRestaurantId(int $id): self
    {
        return $this->andWhere([Category::tableName() . '.restaurant_id' => $id]);
    }
}
