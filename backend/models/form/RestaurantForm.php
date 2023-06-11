<?php

namespace backend\models\form;

use common\base\exception\ValidateException;
use common\base\model\Form;
use common\enums\RestaurantStatus;
use common\models\AR\Restaurant;
use common\models\AR\User;

class RestaurantForm extends Form
{
    public const SCENARIO_CREATE = 'scenario_create';
    public const SCENARIO_UPDATE = 'scenario_update';

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
            ['user_id', 'required'],
            [['name'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'legalName', 'address'], 'string', 'max' => 255],
            [
                ['user_id'],
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $common = [
            'name',
            'legalName',
            'address'
        ];

        return [
            self::SCENARIO_CREATE => [...$common, 'user_id'],
            self::SCENARIO_UPDATE => $common
        ];
    }

    /**
     * @return Restaurant
     * @throws ValidateException
     */
    public function create(): Restaurant
    {
        $this->ifNotValidThrow();

        $restaurant = new Restaurant();

        $restaurant->name = $this->name;
        $restaurant->legal_name = $this->legalName;
        $restaurant->address = $this->address;
        $restaurant->user_id = $this->user_id;
        $restaurant->status = RestaurantStatus::HIDDEN->value;

        $restaurant->saveOrThrow();

        return $restaurant;
    }

    /**
     * @param Restaurant $model
     * @return void
     * @throws ValidateException
     */
    public function update(Restaurant $model): void
    {
        $this->ifNotValidThrow();

        $model->name = $this->name;
        $model->legal_name = $this->legalName;
        $model->address = $this->address;

        $model->saveOrThrow();
    }
}