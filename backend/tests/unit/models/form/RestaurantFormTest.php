<?php

namespace app\tests\unit\models\form;

use app\models\form\RestaurantForm;
use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\fixtures\UserFixture;
use common\models\AR\Restaurant;

class RestaurantFormTest extends Unit
{
    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    public function testValidate(): void
    {
        $testCases = [
            [
                'desc' => 'Valid all',
                'data' => [
                    'user_id' => 1,
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                ],
                'isValid' => true,
            ],
            [
                'desc' => 'name is null',
                'data' => [
                    'user_id' => 1,
                    'legalName' => 'legal name',
                    'address' => 'Address',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Not exist user',
                'data' => [
                    'user_id' => 2,
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'User id is null',
                'data' => [
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                ],
                'isValid' => false,
            ],
        ];

        foreach ($testCases as $tc) {
            $form = new RestaurantForm();
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }

    public function testCreateValid(): void
    {
        $form = new RestaurantForm();
        $form->load([
            'user_id' => 1,
            'legalName' => 'legal name',
            'name' => 'Name',
            'address' => 'Address',
        ]);

        $form->create();

        $model = Restaurant::find()->where([
            'user_id' => 1,
            'legal_name' => 'legal name',
            'name' => 'Name',
            'address' => 'Address',
        ])->one();

        verify($model)->notNull();
    }

    public function testCreateInvalidData(): void
    {
        $form = new RestaurantForm();
        $form->load([
            'legalName' => 'legal name',
            'name' => 'Name',
            'address' => 'Address',
        ]);
        $this->expectException(ValidateException::class);
        $form->create();
    }
}