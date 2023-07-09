<?php

namespace backend\controllers;

use common\base\exception\ValidateException;
use common\helpers\HttpCode;
use common\models\AR\MenuItem;
use common\models\form\MenuItemForm;
use common\services\MenuItemService;
use Throwable;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\widgets\Menu;

class MenuController extends AppController
{
    private MenuItemService $service;

    /**
     * @param $id
     * @param $module
     * @param MenuItemService $service
     * @param array $config
     */
    public function __construct($id, $module, MenuItemService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return MenuItem
     * @throws Throwable
     * @throws ValidateException
     * @throws Exception
     */
    public function actionCreate(): MenuItem
    {
        $form = new MenuItemForm();
        $form->setScenario(MenuItemForm::SCENARIO_CREATE);
        $form->load($this->request->post());

        return $this->service->create($form);
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function actionUpdate(int $id): void
    {
        $form = new MenuItemForm();
        $form->setScenario(MenuItemForm::SCENARIO_UPDATE);
        $form->load($this->request->post());

        $this->service->update($form, $this->findModel($id));
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function actionDelete(int $id): void
    {
        $this->service->delete($this->findModel($id));
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return MenuItem
     * @throws NotFoundHttpException
     */
    private function findModel(int $id): MenuItem
    {
        if ($model = MenuItem::find()->byId($id)->one()) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}