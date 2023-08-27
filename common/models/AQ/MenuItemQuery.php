<?php

namespace common\models\AQ;

use common\base\model\AQ;
use common\enums\Status;
use common\models\AR\MenuItem;
use yii\db\ActiveRecord;

class MenuItemQuery extends AQ
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
        return $this->andWhere([MenuItem::tableName() . '.restaurant_id' => $id]);
    }

    /**
     * @param int $method
     * @return self
     */
    public function orderByOrdering(int $method): self
    {
        return $this->orderBy([MenuItem::tableName() . '.ordering' => $method]);
    }

    /**
     * @return int
     */
    public function lastOrdering(): int
    {
        return $this->orderByOrdering(SORT_DESC)->one()?->ordering ?? 0;
    }

    /**
     * @param int $id
     * @return self
     */
    public function byId(int $id): self
    {
        return $this->andWhere([MenuItem::tableName() . '.id' => $id]);
    }

    /**
     * @param Status $status
     * @return self
     */
    public function withoutStatus(Status $status): self
    {
        return $this->andWhere(['!=', MenuItem::tableName() . '.status', $status->value]);
    }

    /**
     * @return self
     */
    public function notDeleted(): self
    {
        return $this->withoutStatus(Status::Deleted);
    }

    /**
     * @param int $id
     * @return self
     */
    public function byUserId(int $id): self
    {
        return $this->joinWith(['restaurant r'])->andWhere(['r.user_id' => $id]);
    }

    /**
     * @param string $uniqueName
     * @return self
     */
    public function byRestaurantUniqueName(string $uniqueName): self
    {
        return $this->joinWith(['restaurant r'])->andWhere(['r.unique_name' => $uniqueName]);
    }
}