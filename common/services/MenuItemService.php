<?php

namespace common\services;

use common\base\exception\SaveModelException;
use common\base\exception\ValidateException;
use common\components\FileUploader;
use common\enums\Status;
use common\models\AR\File;
use common\models\AR\MenuItem;
use common\models\form\MenuItemForm;
use common\models\form\MenuItemImageUploadForm;
use Throwable;
use yii\db\Connection;
use yii\db\Exception;

readonly class MenuItemService
{
    /**
     * @param Connection $db
     * @param FileUploader $fileUploader
     */
    public function __construct(private Connection $db, private FileUploader $fileUploader)
    {
    }

    /**
     * @throws Exception
     * @throws Throwable
     * @throws ValidateException
     */
    public function create(MenuItemForm $form): MenuItem
    {
        $tx = $this->db->beginTransaction();

        try {
            $form->ifNotValidThrow();

            $model = new MenuItem();

            $model->restaurant_id = $form->restaurant_id;
            $model->category_id = $form->category_id;
            $model->status = $form->status;
            $model->title = $form->title;
            $model->description = $form->description;
            $model->price = $form->price;
            $model->sale_price = $form->sale_price;
            $model->unit_id = $form->unit_id;
            $model->generateOrdering();

            $model->saveOrThrow();

            $tx->commit();
            return $model;
        } catch (Throwable $th) {
            $tx->rollBack();
            throw $th;
        }
    }

    /**
     * @param MenuItemForm $form
     * @param MenuItem $model
     * @return void
     * @throws SaveModelException
     * @throws ValidateException
     */
    public function update(MenuItemForm $form, MenuItem $model): void
    {
        $form->ifNotValidThrow();

        $model->category_id = $form->category_id;
        $model->title = $form->title;
        $model->description = $form->description;
        $model->status = $form->status;
        $model->price = $form->price;
        $model->sale_price = $form->sale_price;
        $model->unit_id = $form->unit_id;

        $model->saveOrThrow();
    }

    /**
     * @param MenuItem $model
     * @return void
     * @throws SaveModelException
     */
    public function delete(MenuItem $model): void
    {
        $model->setStatus(Status::Deleted);
        $model->generateDeletedAt();
        $model->saveOrThrow();
    }

    /**
     * @param MenuItem $model
     * @param MenuItemImageUploadForm $form
     * @return File
     * @throws Exception
     * @throws Throwable
     * @throws ValidateException
     */
    public function uploadImage(MenuItemImageUploadForm $form, MenuItem $model): File
    {
        $tx = $this->db->beginTransaction();

        try {
            $form->ifNotValidThrow();
            $file = $this->fileUploader->upload($form->image);

            $model->file_id = $file->id;
            $model->saveOrThrow();

            $tx->commit();
            return $file;
        } catch (Throwable $th) {
            $tx->rollBack();
            throw $th;
        }
    }
}