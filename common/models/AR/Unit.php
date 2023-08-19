<?php

namespace common\models\AR;

use common\base\model\AR;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $value
 *
 * @property MenuItem[] $menuItems
 */
class Unit extends AR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['value'], 'required'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
        ];
    }

    /**
     * Gets query for [[MenuItems]].
     *
     * @return ActiveQuery
     */
    public function getMenuItems(): ActiveQuery
    {
        return $this->hasMany(MenuItem::class, ['unit_id' => 'id']);
    }
}
