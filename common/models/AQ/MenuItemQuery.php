<?php

namespace common\models\AQ;

use common\models\AR\MenuItem;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class MenuItemQuery extends ActiveQuery
{

    /**
     * @param $db
     * @return array|ActiveRecord|MenuItem
     */
    public function one($db = null): null|ActiveRecord|MenuItem
    {
        $this->limit(1);
        return parent::one($db);
    }

    /**
     * @param $db
     * @return array|MenuItem[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @param int $id
     * @return self
     */
    public function byRestaurantId(int $id): self
    {
        return $this->andWhere(['restaurant_id' => $id]);
    }

    /**
     * @return self
     */
    public function orderByOrdering(): self
    {
        return $this->orderBy(['ordering' => SORT_DESC]);
    }

    /**
     * @return int
     */
    public function lastOrdering(): int
    {
        return $this->orderByOrdering()->one()?->ordering ?? 0;
    }
}