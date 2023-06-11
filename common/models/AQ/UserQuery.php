<?php

namespace common\models\AQ;

use common\enums\UserStatus;
use common\models\AR\User;
use common\models\AR\UserAccessToken;
use yii\db\ActiveQuery;
use yii\db\Expression;

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

    /**
     * @return self
     */
    public function active(): self
    {
        return $this->andWhere([User::tableName() . '.status' => UserStatus::Active->value]);
    }

    /**
     * @param string $token
     * @return self
     */
    public function byAccessToken(string $token): self
    {
        return $this->leftJoin(
            UserAccessToken::tableName(),
            [UserAccessToken::tableName() . '.user_id' => new Expression(User::tableName() . '.id')],
        )->andWhere([UserAccessToken::tableName() . '.token' => $token]);
    }
}