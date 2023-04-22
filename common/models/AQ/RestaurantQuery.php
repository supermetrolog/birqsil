<?php

namespace common\models\AQ;

use common\models\AR\Restaurant;
use yii\db\ActiveQuery;

class RestaurantQuery extends ActiveQuery
{
    /**
     * @param $db
     * @return array|Restaurant|null
     */
    public function one($db = null): array|Restaurant|null
    {
        $this->limit(1);
        return parent::one($db);
    }

    /**
     * @param $db
     * @return array|Restaurant[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @param int $id
     * @return self
     */
    public function byUserID(int $id): self
    {
        return $this->andWhere(['user_id' => $id]);
    }

    /**
     * @param int $id
     * @return self
     */
    public function byID(int $id): self
    {
        return $this->andWhere(['id' => $id]);
    }
}