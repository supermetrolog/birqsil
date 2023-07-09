<?php

namespace common\services;

use common\base\exception\ValidateException;
use common\models\AR\MenuItem;
use common\models\form\MenuItemForm;
use Throwable;
use yii\db\Connection;
use yii\db\Exception;

readonly class MenuItemService
{
    public function __construct(private Connection $db)
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
            $model->status = $form->status;
            $model->title = $form->title;
            $model->description = $form->description;

            $model->ordering = MenuItem::find()->lastOrdering() + 1;

            $model->saveOrThrow();

            $tx->commit();
            return $model;
        } catch (Throwable $th) {
            $tx->rollBack();
            throw $th;
        }
    }
}