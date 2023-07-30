<?php

namespace common\models\AR;

use common\base\model\AR;
use common\enums\Status;
use common\models\AQ\MenuItemQuery;
use DateTime;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property int $restaurant_id
 * @property string $title
 * @property string|null $description
 * @property int $status
 * @property int $ordering
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $file_id
 *
 * @property Restaurant $restaurant
 * @property File $image
 */
class MenuItem extends AR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'menu_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['restaurant_id', 'title', 'ordering'], 'required'],
            [['restaurant_id', 'status', 'ordering', 'file_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'description'], 'string', 'max' => 255],
            [['ordering'], 'unique'],
            [['restaurant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::class, 'targetAttribute' => ['restaurant_id' => 'id']],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['file_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'restaurant_id' => 'Restaurant ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'ordering' => 'Ordering',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return ActiveQuery
     */
    public function getRestaurant(): ActiveQuery
    {
        return $this->hasOne(Restaurant::class, ['id' => 'restaurant_id']);
    }

    /**
     * @return MenuItemQuery
     */
    public static function find(): MenuItemQuery
    {
        return new MenuItemQuery(get_called_class());
    }

    /**
     * @return void
     */
    public function generateDeletedAt(): void
    {
        $this->deleted_at = (new DateTime())->format('Y-m-d H:i:s');
    }

    /**
     * @return void
     */
    public function generateOrdering(): void
    {
        $this->ordering = self::find()->lastOrdering() + 1;
    }
    /**
     * @param Status $status
     * @return $this
     */
    public function setStatus(Status $status): self
    {
        $this->status = $status->value;
        return $this;
    }

    /**
     * @return ActiveQuery
     */
    public function getImage(): ActiveQuery
    {
        return $this->hasOne(File::class, ['id' => 'file_id']);
    }
}
