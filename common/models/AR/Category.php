<?php

namespace common\models\AR;

use common\base\model\AR;
use common\models\AQ\CategoryQuery;
use common\models\AQ\RestaurantQuery;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $name
 * @property int $restaurant_id
 * @property int $ordering
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Restaurant $restaurant
 */
class Category extends AR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'restaurant_id', 'ordering'], 'required'],
            [['restaurant_id', 'ordering'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['restaurant_id', 'ordering'], 'unique', 'targetAttribute' => ['restaurant_id', 'ordering']],
            [['restaurant_id', 'name'], 'unique', 'targetAttribute' => ['restaurant_id', 'name']],
            [['restaurant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::class, 'targetAttribute' => ['restaurant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'restaurant_id' => 'Restaurant ID',
            'ordering' => 'Ordering',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery|RestaurantQuery
     */
    public function getRestaurant(): ActiveQuery|RestaurantQuery
    {
        return $this->hasOne(Restaurant::class, ['id' => 'restaurant_id']);
    }

    /**
     * {@inheritdoc}
     * @return CategoryQuery
     */
    public static function find(): CategoryQuery
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * @return void
     */
    public function generateOrdering(): void
    {
        $this->ordering = self::find()->lastOrdering() + 1;
    }
}
