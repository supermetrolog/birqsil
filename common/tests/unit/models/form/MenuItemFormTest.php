<?php

namespace common\tests\unit\models\form;

use Codeception\Test\Unit;
use common\enums\Status;
use common\fixtures\RestaurantFixture;
use common\models\form\MenuItemForm;

class MenuItemFormTest extends Unit
{
    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => RestaurantFixture::class,
                'dataFile' => codecept_data_dir() . 'restaurant.php'
            ]
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
                'isValid' => false,
            ],
            [
                'desc' => 'Invalid restaurant ID',
                'data' => [
                    'restaurant_id' => 55,
                    'title' => 'Test',
                    'description' => 'Desc',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Invalid title',
                'data' => [
                    'restaurant_id' => 1,
                    'title' => 'TTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                    'description' => 'Desc',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Invalid description',
                'data' => [
                    'restaurant_id' => 1,
                    'title' => 'title',
                    'description' => 'TTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                ],
                'isValid' => false,
            ],
        ];

        foreach ($testCases as $tc) {
            $form = new MenuItemForm();
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['desc'] . json_encode($form->getErrors()));
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }
}