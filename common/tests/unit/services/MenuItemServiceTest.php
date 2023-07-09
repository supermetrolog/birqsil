<?php

namespace common\tests\unit\services;

use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\enums\Status;
use common\fixtures\RestaurantFixture;
use common\models\form\MenuItemForm;
use common\services\MenuItemService;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class MenuItemServiceTest extends Unit
{
    public function _fixtures(): array
    {
        return [
            'restaurant' => [
                'class' => RestaurantFixture::class,
                'dataFile' => codecept_data_dir() . 'restaurant.php'
            ],
        ];
    }

    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    private function getService(): MenuItemService
    {
        return Yii::$container->get(MenuItemService::class);
    }

    public function testCreateValid(): void
    {
        $form = new MenuItemForm();
        $form->load([
            'restaurant_id' => 1,
            'title' => 'Test',
        ]);

        $service = $this->getService();

        $model = $service->create($form);

        verify($model->ordering)->equals(1);
        verify($model->status)->equals(Status::Active->value);
    }

    public function testCreateInvalid(): void
    {
        $form = new MenuItemForm();
        $form->load([
            'restaurant_id' => 22,
            'title' => 'Test',
        ]);

        $service = $this->getService();

        $this->expectException(ValidateException::class);
        $service->create($form);
    }

    public function testDoubleCreateOrdering(): void
    {
        $form = new MenuItemForm();
        $form->load([
            'restaurant_id' => 1,
            'title' => 'Test',
        ]);

        $service = $this->getService();

        $model = $service->create($form);

        verify($model->ordering)->equals(1);

        $model = $service->create($form);
        verify($model->ordering)->equals(2);
    }
}