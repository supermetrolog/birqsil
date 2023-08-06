<?php

namespace common\tests\unit\services;

use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\components\FileUploader;
use common\enums\Status;
use common\fixtures\CategoryFixture;
use common\fixtures\FileFixture;
use common\fixtures\MenuItemFixture;
use common\fixtures\RestaurantFixture;
use common\models\AR\File;
use common\models\AR\MenuItem;
use common\models\form\MenuItemForm;
use common\models\form\MenuItemImageUploadForm;
use common\services\MenuItemService;
use PHPUnit\Framework\MockObject\MockObject;
use Yii;
use yii\web\UploadedFile;

class MenuItemServiceTest extends Unit
{
    private FileUploader|MockObject $fileUploader;

    protected function _before()
    {
        $this->fileUploader = $this->createMock(FileUploader::class, []);
    }

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
            'category' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir('category.php')
            ],
            'file' => [
                'class' => FileFixture::class,
                'dataFile' => codecept_data_dir() . 'file.php'
            ]
        ];
    }

    /**
     * @return MenuItemService
     */
    private function getService(): MenuItemService
    {
        return new MenuItemService(Yii::$app->db, $this->fileUploader);
    }

    public function testCreateValid(): void
    {
        $form = new MenuItemForm();
        $form->setScenario(MenuItemForm::SCENARIO_CREATE);
        $form->load([
            'restaurant_id' => 1,
            'category_id' => 1,
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
            'category_id' => 1,
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
            'category_id' => 1,
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
            'restaurant_id' => 1,
            'category_id' => 1,
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
            'category_id' => 1,
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

        $file = $this->createMock(File::class);
        $file->method('__get')->with('id')->willReturn(1);
        $this->fileUploader->method('upload')->willReturn($file);

        $service = $this->getService();

        $service->uploadImage($form, $model);

        $model = MenuItem::find()->byId(1)->one();
        verify($model->file_id)->notNull($file->id);
        verify($model->file_id)->equals($file->id);
        verify(File::find()->where(['id' => $model->file_id])->exists())->true();
    }
}