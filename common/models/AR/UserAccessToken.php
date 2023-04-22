<?php

namespace common\models\AR;

use common\base\model\AR;
use common\enums\Status;
use common\models\AQ\UserAccessTokenQuery;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "user_access_token".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $token
 * @property string $created_at
 * @property int $status
 * @property int $expire Token lifetime in seconds
 *
 * @property User $user
 */
class UserAccessToken extends AR
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'user_access_token';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['user_id', 'status', 'expire'], 'required'],
            [['user_id', 'status', 'expire'], 'integer'],
            ['status', 'default', 'value' => Status::Active->value],
            ['status', 'in', 'range' => [
                Status::Active->value,
                Status::Inactive->value,
            ]],
            [['created_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'created_at' => 'Created At',
            'status' => 'Status',
            'expire' => 'Expire',
        ];
    }

    /**
     * @return void
     * @throws Exception
     */
    public function generateToken(): void
    {
        $this->token = Yii::$app->security->generateRandomString();
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return UserAccessTokenQuery
     */
    public static function find(): UserAccessTokenQuery
    {
        return new UserAccessTokenQuery(get_called_class());
    }
}
