<?php

namespace backend\models\form;

use common\base\exception\ValidateException;
use common\base\model\Form;
use common\enums\RestaurantStatus;
use common\helpers\RandomHelper;
use common\models\AQ\RestaurantQuery;
use common\models\AR\Restaurant;
use common\models\AR\User;
use yii\base\Exception;
use yii\db\ActiveQuery;

class RestaurantForm extends Form
{
    public const SCENARIO_CREATE = 'scenario_create';
    public const SCENARIO_UPDATE = 'scenario_update';

    public int|null $id = null;
    public int|null $user_id = null;
    public string|null $name = null;
    public string|null $unique_name = null;
    public string|null $legalName = null;
    public string|null $address = null;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['user_id', 'required'],
            ['id', 'required'],
            ['unique_name', 'required', 'on' => self::SCENARIO_UPDATE],
            [['name'], 'required'],
            [['user_id', 'id'], 'integer'],
            [['name', 'legalName', 'address'], 'string', 'max' => 255],
            [
                'unique_name',
                'unique',
                'targetClass' => Restaurant::class,
                'targetAttribute' => ['unique_name' => 'unique_name'],
                'filter' => function (RestaurantQuery $query) {
                    if ($this->scenario === self::SCENARIO_UPDATE) {
                        $query->withoutId($this->id);
                    }
                }
            ],
            [
                'user_id',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
            [
                'id',
                'exist',
                'targetClass' => Restaurant::class,
                'targetAttribute' => ['id' => 'id'],
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
            'address',
            'unique_name'
        ];

        return [
            self::SCENARIO_CREATE => [...$common, 'user_id'],
            self::SCENARIO_UPDATE => [...$common, 'id']
        ];
    }

    /**
     * @return Restaurant
     * @throws ValidateException
     * @throws Exception
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
        $restaurant->unique_name = $this->unique_name ?? RandomHelper::randomString(32);

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
        $model->unique_name = $this->unique_name;

        $model->saveOrThrow();
    }
}