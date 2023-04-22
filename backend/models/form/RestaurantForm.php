<?php

namespace app\models\form;

use common\base\exception\ValidateException;
use common\base\model\Form;
use common\models\AR\Restaurant;
use common\models\AR\User;

class RestaurantForm extends Form
{
    public int|null $user_id = null;
    public string|null $name = null;
    public string|null $legalName = null;
    public string|null $address = null;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['user_id', 'name'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'legalName', 'address'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return void
     * @throws ValidateException
     */
    public function create(): void
    {
        $this->ifNotValidThrow();

        $restaurant = new Restaurant();

        $restaurant->name = $this->name;
        $restaurant->legal_name = $this->legalName;
        $restaurant->address = $this->address;
        $restaurant->user_id = $this->user_id;

        $restaurant->saveOrThrow();
    }
}