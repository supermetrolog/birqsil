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
}
