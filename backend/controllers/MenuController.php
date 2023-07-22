<?php

namespace backend\controllers;

use common\base\exception\ValidateException;
use common\helpers\HttpCode;
use common\models\AR\MenuItem;
use common\models\form\MenuItemForm;
use common\models\form\MenuItemImageUploadForm;
use common\services\MenuItemService;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\User;

class MenuController extends AppController
{
    private MenuItemService $service;
    private User $user;

    /**
     * @param $id
     * @param $module
     * @param User $user
     * @param MenuItemService $service
     * @param array $config
     */
    public function __construct($id, $module, User $user, MenuItemService $service, array $config = [])
    {
        $this->service = $service;
        $this->user = $user;

        parent::__construct($id, $module, $config);
    }

    /**
     * @param int $restaurant_id
     * @return ActiveDataProvider
     */
    public function actionIndex(int $restaurant_id): ActiveDataProvider
    {
        $query = MenuItem::find()
            ->byUserId($this->user->getId())
            ->byRestaurantId($restaurant_id)
            ->notDeleted();

        return new ActiveDataProvider([
           'query' => $query
        ]);
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
     * @return MenuItem
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function actionUpdate(int $id): MenuItem
    {
        $form = new MenuItemForm();
        $form->setScenario(MenuItemForm::SCENARIO_UPDATE);
        $form->load($this->request->post());

        $model = $this->findModel($id);
        $this->service->update($form, $model);

        return $model;
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
     * @return void
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws ValidateException
     */
    public function actionUploadFile(int $id): void
    {
        $form = new MenuItemImageUploadForm();
        $form->image = UploadedFile::getInstance($form, 'image');

        $this->service->uploadImage($form, $this->findModel($id));
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return MenuItem
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): MenuItem
    {
        return $this->findModel($id);
    }

    /**
     * @param int $id
     * @return MenuItem
     * @throws NotFoundHttpException
     */
    private function findModel(int $id): MenuItem
    {
        if ($model = MenuItem::find()
            ->byId($id)
            ->byUserId($this->user->getId())
            ->notDeleted()
            ->one()
        ) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}