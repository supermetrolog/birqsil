<?php

namespace backend\models\form;

use common\base\exception\ValidateException;
use common\base\model\Form;
use common\models\AQ\CategoryQuery;
use common\models\AR\Category;
use common\models\AR\Restaurant;

class CategoryForm extends Form
{
    public const SCENARIO_CREATE = 'scenario_create';
    public const SCENARIO_UPDATE = 'scenario_update';

    public int|null $id = null;
    public string|null $name = null;
    public int|null $restaurant_id = null;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'restaurant_id', 'id'], 'required'],
            ['name', 'string', 'max' => 255],
            [['restaurant_id', 'id'], 'integer'],
            [
                ['restaurant_id', 'name'],
                'unique',
                'targetClass' => Category::class,
                'targetAttribute' => ['restaurant_id', 'name'],
                'filter' => function (CategoryQuery $query) {
                    if ($this->scenario === self::SCENARIO_UPDATE) {
                        $query->withoutId($this->id);
                    }
                }
            ],
            [
                'restaurant_id',
                'exist',
                'targetClass' => Restaurant::class,
                'targetAttribute' => ['restaurant_id' => 'id']
            ],
            [
                'id',
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => ['id' => 'id']
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        $common = [
            'name',
            'restaurant_id'
        ];

        return [
            self::SCENARIO_CREATE => $common,
            self::SCENARIO_UPDATE => [...$common, 'id'],
        ];
    }

    /**
     * @return Category
     * @throws ValidateException
     */
    public function create(): Category
    {
        $this->ifNotValidThrow();

        $model = new Category();

        $model->name = $this->name;
        $model->restaurant_id = $this->restaurant_id;
        $model->generateOrdering();

        $model->saveOrThrow();

        return $model;
    }

    /**
     * @param Category $model
     * @return void
     * @throws ValidateException
     */
    public function update(Category $model): void
    {
        $this->ifNotValidThrow();

        $model->name = $this->name;

        $model->saveOrThrow();
    }
}