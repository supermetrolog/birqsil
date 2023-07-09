<?php

namespace common\tests\unit\models\form;

use Codeception\Test\Unit;
use common\enums\Status;
use common\fixtures\MenuItemFixture;
use common\fixtures\RestaurantFixture;
use common\models\form\MenuItemForm;

class MenuItemFormTest extends Unit
{
    public function _fixtures(): array
    {
        return [
            'restaurant' => [
                'class' => RestaurantFixture::class,
                'dataFile' => codecept_data_dir() . 'restaurant.php'
            ],
            'menu_item' => [
                'class' => MenuItemFixture::class,
                'dataFile' => codecept_data_dir() . 'menu_item.php'
            ],
        ];
    }

    public function testValidate(): void
    {
        $testCases = [
            [
                'desc' => 'Valid data',
                'data' => [
                    'restaurant_id' => 1,
                    'title' => 'Test',
                    'description' => 'Desc',
                    'status' => Status::Active->value
                ],
                'scenario' => MenuItemForm::SCENARIO_CREATE,
                'isValid' => true,
            ],
            [
                'desc' => 'Valid data update',
                'data' => [
                    'id' => 1,
                    'title' => 'Test',
                    'description' => 'Desc',
                    'status' => Status::Active->value
                ],
                'scenario' => MenuItemForm::SCENARIO_UPDATE,
                'isValid' => true,
            ],
            [
                'desc' => 'Invalid status',
                'data' => [
                    'restaurant_id' => 1,
                    'title' => 'Test',
                    'description' => 'Desc',
                    'status' => 55
                ],
                'scenario' => MenuItemForm::SCENARIO_CREATE,
                'isValid' => false,
            ],
            [
                'desc' => 'Invalid restaurant ID',
                'data' => [
                    'restaurant_id' => 55,
                    'title' => 'Test',
                    'description' => 'Desc',
                ],
                'scenario' => MenuItemForm::SCENARIO_CREATE,
                'isValid' => false,
            ],
            [
                'desc' => 'Invalid title',
                'data' => [
                    'restaurant_id' => 1,
                    'title' => 'TTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                    'description' => 'Desc',
                ],
                'scenario' => MenuItemForm::SCENARIO_CREATE,
                'isValid' => false,
            ],
            [
                'desc' => 'Invalid description',
                'data' => [
                    'restaurant_id' => 1,
                    'title' => 'title',
                    'description' => 'TTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                ],
                'scenario' => MenuItemForm::SCENARIO_CREATE,
                'isValid' => false,
            ],
        ];

        foreach ($testCases as $tc) {
            $form = new MenuItemForm();
            $form->setScenario($tc['scenario']);
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['desc'] . json_encode($form->getErrors()));
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }
}