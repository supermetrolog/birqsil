<?php

namespace common\models\form;

use common\base\model\Form;
use common\enums\Status;
use common\models\AR\Category;
use common\models\AR\Restaurant;
use common\models\AR\Unit;

class MenuItemForm extends Form
{
    public const SCENARIO_CREATE = 'scenario_create';
    public const SCENARIO_UPDATE = 'scenario_update';

    public int|null $restaurant_id = null;
    public int|null $category_id = null;
    public string|null $title = null;
    public string|null $description = null;
    public int|null $status = Status::Active->value;
    public int|null $unit_id = null;
    public int|null $price = null;
    public int|null $sale_price = null;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['restaurant_id', 'title', 'status', 'category_id', 'price'], 'required'],
            [['restaurant_id', 'status', 'category_id', 'unit_id', 'price', 'sale_price'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
            ['status', 'in', 'range' => Status::asArray()],
            [['restaurant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::class, 'targetAttribute' => ['restaurant_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id', 'restaurant_id' => 'restaurant_id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $common = [
            'status',
            'title',
            'description',
            'category_id',
            'restaurant_id',
            'price',
            'sale_price',
            'unit_id'
        ];

        return [
            self::SCENARIO_CREATE => $common,
            self::SCENARIO_UPDATE => $common
        ];
    }
}