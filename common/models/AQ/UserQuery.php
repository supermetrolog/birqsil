<?php

namespace common\models\AQ;

use common\models\AR\User;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    /**
     * @param $db
     * @return array|User|null
     */
    public function one($db = null): array|User|null
    {
        $this->limit(1);
        return parent::one($db);
    }

    /**
     * @param $db
     * @return array|User[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @param string $email
     * @return self
     */
    public function byEmail(string $email): self
    {
        return $this->andWhere(['email' => $email]);
    }
}