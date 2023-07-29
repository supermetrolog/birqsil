<?php

namespace backend\tests\unit\models\form;

use backend\models\form\RestaurantForm;
use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\enums\RestaurantStatus;
use common\fixtures\RestaurantFixture;
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
            ],
            'restaurant' => [
                'class' => RestaurantFixture::class,
                'dataFile' => codecept_data_dir() . 'restaurant.php'
            ]
        ];
    }

    public function testValidate(): void
    {
        $testCases = [
            [
                'desc' => 'Valid create',
                'data' => [
                    'user_id' => 1,
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                ],
                'isValid' => true,
                'on' => RestaurantForm::SCENARIO_CREATE
            ],
            [
                'desc' => 'name is null',
                'data' => [
                    'user_id' => 1,
                    'legalName' => 'legal name',
                    'address' => 'Address',
                ],
                'isValid' => false,
                'on' => RestaurantForm::SCENARIO_CREATE
            ],
            [
                'desc' => 'Not exist user',
                'data' => [
                    'user_id' => 23131,
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                ],
                'isValid' => false,
                'on' => RestaurantForm::SCENARIO_CREATE
            ],
            [
                'desc' => 'User id is null',
                'data' => [
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                ],
                'isValid' => false,
                'on' => RestaurantForm::SCENARIO_CREATE
            ],
            [
                'desc' => 'Valid update',
                'data' => [
                    'id' => 1,
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                    'unique_name' => '11111',
                ],
                'isValid' => true,
                'on' => RestaurantForm::SCENARIO_UPDATE
            ],
            [
                'desc' => 'Invalid unique when create',
                'data' => [
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                    'unique_name' => '11111',
                ],
                'isValid' => false,
                'on' => RestaurantForm::SCENARIO_CREATE
            ],
            [
                'desc' => 'Valid unique when create',
                'data' => [
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                    'unique_name' => '33333',
                ],
                'isValid' => false,
                'on' => RestaurantForm::SCENARIO_CREATE
            ],
            [
                'desc' => 'Valid unique when update',
                'data' => [
                    'id' => 1,
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                    'unique_name' => '11111',
                ],
                'isValid' => true,
                'on' => RestaurantForm::SCENARIO_UPDATE
            ],
            [
                'desc' => 'Invalid unique when update',
                'data' => [
                    'id' => 1,
                    'legalName' => 'legal name',
                    'name' => 'Name',
                    'address' => 'Address',
                    'unique_name' => '22222',
                ],
                'isValid' => false,
                'on' => RestaurantForm::SCENARIO_UPDATE
            ],
        ];

        foreach ($testCases as $tc) {
            $form = new RestaurantForm(['scenario' => $tc['on']]);
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }

    public function testCreateValid(): void
    {
        $form = new RestaurantForm(['scenario' => RestaurantForm::SCENARIO_CREATE]);
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
        verify($model->status)->equals(RestaurantStatus::HIDDEN->value);
        verify($model->unique_name)->notNull();
    }

    public function testCreateInvalidData(): void
    {
        $form = new RestaurantForm(['scenario' => RestaurantForm::SCENARIO_CREATE]);
        $form->load([
            'legalName' => 'legal name',
            'name' => 'Name',
            'address' => 'Address',
        ]);
        $this->expectException(ValidateException::class);
        $form->create();
    }

    public function testUpdateValid(): void
    {
        $form = new RestaurantForm(['scenario' => RestaurantForm::SCENARIO_UPDATE]);
        $model = Restaurant::find()->byID(1)->one();

        $form->load([
            'id' => $model->id,
            'legalName' => 'new legal name',
            'name' => 'New Name',
            'address' => 'New Address',
            'unique_name' => '123',
        ]);

        $form->update($model);

        $model->refresh();

        verify($model->legal_name)->equals('new legal name');
        verify($model->name)->equals('New Name');
        verify($model->address)->equals('New Address');

    }

    public function testUpdateInvalidData(): void
    {
        $form = new RestaurantForm(['scenario' => RestaurantForm::SCENARIO_UPDATE]);
        $model = Restaurant::find()->byID(1)->one();
        $form->load([
            'id' => $model->id,
            'legalName' => 'legal name',
            'address' => 'Address',
        ]);

        $this->expectException(ValidateException::class);
        $form->update($model);
    }
}