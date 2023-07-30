<?php

namespace backend\tests\unit\models\form;

use backend\models\form\CategoryForm;
use Codeception\Test\Unit;
use common\fixtures\CategoryFixture;
use common\fixtures\RestaurantFixture;
use yii\helpers\Json;

class CategoryFormTest extends Unit
{

    public function _fixtures(): array
    {
        return [
            'category' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir('category.php')
            ],
            'restaurant' => [
                'class' => RestaurantFixture::class,
                'dataFile' => codecept_data_dir('restaurant.php')
            ]
        ];
    }

    public function testValidate(): void
    {
        $testCases = [
            [
                'name' => 'Valid create',
                'data' => [
                    'name' => 'Test Name',
                    'restaurant_id' => 1,
                ],
                'isValid' => true,
                'on' => CategoryForm::SCENARIO_CREATE
            ],
            [
                'name' => 'Invalid restaurant id',
                'data' => [
                    'name' => 'Test Name',
                    'restaurant_id' => 999,
                ],
                'isValid' => false,
                'on' => CategoryForm::SCENARIO_CREATE
            ],
            [
                'name' => 'Duplicate name',
                'data' => [
                    'name' => 'test',
                    'restaurant_id' => 1,
                ],
                'isValid' => false,
                'on' => CategoryForm::SCENARIO_CREATE
            ],
            [
                'name' => 'Valid update',
                'data' => [
                    'id' => 1,
                    'name' => 'test',
                    'restaurant_id' => 1,
                ],
                'isValid' => true,
                'on' => CategoryForm::SCENARIO_UPDATE
            ],
            [
                'name' => 'Invalid restaurant id when update',
                'data' => [
                    'id' => 1,
                    'name' => 'test',
                    'restaurant_id' => 999,
                ],
                'isValid' => false,
                'on' => CategoryForm::SCENARIO_UPDATE
            ],
            [
                'name' => 'Duplicate name when update',
                'data' => [
                    'id' => 1,
                    'name' => 'test2',
                    'restaurant_id' => 2,
                ],
                'isValid' => false,
                'on' => CategoryForm::SCENARIO_UPDATE
            ],
            [
                'name' => 'Invalid ID',
                'data' => [
                    'id' => 999,
                    'name' => 'test',
                    'restaurant_id' => 1,
                ],
                'isValid' => false,
                'on' => CategoryForm::SCENARIO_UPDATE
            ]
        ];

        foreach ($testCases as $tc) {
            $form = new CategoryForm(['scenario' => $tc['on']]);
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['name'] . ". Errors: " . Json::encode($form->getErrors()));
        }
    }
}