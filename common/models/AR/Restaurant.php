<?php

namespace common\models\AR;

use common\base\model\AR;
use common\enums\RestaurantStatus;
use common\models\AQ\RestaurantQuery;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "restaurant".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $address
 * @property int $status
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property User $user
 */
class Restaurant extends AR
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'restaurant';
    }


    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['user_id', 'name'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'legal_name', 'address'], 'string', 'max' => 255],
            ['status', 'default', 'value' => RestaurantStatus::HIDDEN->value],
            ['status', 'in', 'range' => RestaurantStatus::toArray()],
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
            'name' => 'Name',
            'legal_name' => 'Legal Name',
            'address' => 'Address',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }


    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return RestaurantQuery
     */
    public static function find(): RestaurantQuery
    {
        return new RestaurantQuery(get_called_class());
    }

    /**
     * @param RestaurantStatus $status
     * @return void
     */
    public function setStatus(RestaurantStatus $status): void
    {
        $this->status = $status->value;
    }
}
