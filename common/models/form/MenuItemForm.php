<?php

namespace common\models\form;

use common\base\model\Form;
use common\enums\Status;
use common\models\AR\Restaurant;

class MenuItemForm extends Form
{
    public int|null $restaurant_id = null;
    public string|null $title = null;
    public string|null $description = null;
    public int|null $status = Status::Active->value;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['restaurant_id', 'title', 'status'], 'required'],
            [['restaurant_id', 'status'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
            ['status', 'in', 'range' => Status::asArray()],
            [['restaurant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::class, 'targetAttribute' => ['restaurant_id' => 'id']],
        ];
    }
}