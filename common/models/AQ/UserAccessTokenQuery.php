<?php

namespace common\models\AQ;

use common\models\AR\UserAccessToken;
use yii\db\ActiveQuery;

class UserAccessTokenQuery extends ActiveQuery
{
    /**
     * @param $db
     * @return array|UserAccessToken|null
     */
    public function one($db = null): array|UserAccessToken|null
    {
        $this->limit(1);
        return parent::one($db);
    }

    /**
     * @param $db
     * @return array|UserAccessToken[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @param string $token
     * @return self
     */
    public function byToken(string $token): self
    {
        return $this->andWhere(['token' => $token]);
    }

    /**
     * @param int $id
     * @return self
     */
    public function byUserID(int $id): self
    {
        return $this->andWhere(['user_id' => $id]);
    }
}