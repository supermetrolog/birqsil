<?php

namespace common\tests\unit\services;

use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\enums\Status;
use common\fixtures\MenuItemFixture;
use common\fixtures\RestaurantFixture;
use common\models\AR\File;
use common\models\AR\MenuItem;
use common\models\form\MenuItemForm;
use common\models\form\MenuItemImageUploadForm;
use common\services\MenuItemService;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\web\UploadedFile;

class MenuItemServiceTest extends Unit
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
            ]
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
        $form->setScenario(MenuItemForm::SCENARIO_CREATE);
        $form->load([
            'restaurant_id' => 1,
            'title' => 'Test',
        ]);

        $service = $this->getService();

        $model = $service->create($form);

        verify($model->ordering)->equals(MenuItem::find()->lastOrdering());
        verify($model->status)->equals(Status::Active->value);
    }

    public function testCreateInvalid(): void
    {
        $form = new MenuItemForm();
        $form->setScenario(MenuItemForm::SCENARIO_CREATE);
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
        $form->setScenario(MenuItemForm::SCENARIO_CREATE);
        $form->load([
            'restaurant_id' => 1,
            'title' => 'Test',
        ]);

        $service = $this->getService();

        $model = $service->create($form);

        verify($model->ordering)->equals(MenuItem::find()->lastOrdering());

        $model = $service->create($form);
        verify($model->ordering)->equals(MenuItem::find()->lastOrdering());
    }

    public function testUpdateValid(): void
    {
        $form = new MenuItemForm();
        $form->setScenario(MenuItemForm::SCENARIO_UPDATE);
        $form->load([
            'title' => 'Test2',
            'description' => 'Test2'
        ]);

        $model = MenuItem::find()->byId(1)->one();

        $service = $this->getService();
        $service->update($form, $model);

        verify($model->title)->equals($form->title);
        verify($model->description)->equals($form->description);
    }

    public function testUpdateInvalid(): void
    {
        $form = new MenuItemForm();
        $form->setScenario(MenuItemForm::SCENARIO_UPDATE);
        $form->load([
            'title' => null,
            'description' => 'Test2'
        ]);

        $model = MenuItem::find()->byId(1)->one();

        $service = $this->getService();

        $this->expectException(ValidateException::class);

        $service->update($form, $model);
    }

    public function testDelete(): void
    {
        $model = MenuItem::find()->byId(1)->one();
        $service = $this->getService();
        $service->delete($model);

        $model = MenuItem::find()->byId(1)->one();

        verify($model->deleted_at)->notNull();
        verify($model->status)->equals(Status::Deleted->value);
    }

    public function testUploadImage(): void
    {
        $file = $this->createMock(UploadedFile::class);

        $file->type = 'image/jpeg';
        $file->size = 123442;
        $file->tempName = codecept_data_dir('JPEG-FILE.jpeg');
        $file->method('getBaseName')->willReturn('JPEG-FILE.jpeg');
        $file->method('getExtension')->willReturn('jpeg');
        $file->method('saveAs')->willReturn(true);

        $form = $this->createMock(MenuItemImageUploadForm::class);
        $form->image = $file;
        $form->method('ifNotValidThrow');

        $model = MenuItem::find()->byId(1)->one();


        $service = $this->getService();

        $service->uploadImage($form, $model);

        $model = MenuItem::find()->byId(1)->one();
        verify($model->file_id)->notNull();
        verify(File::find()->where(['id' => $model->file_id])->exists())->true();
    }
}