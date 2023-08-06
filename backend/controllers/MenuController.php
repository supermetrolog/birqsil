<?php

namespace backend\controllers;

use common\base\exception\ValidateException;
use common\helpers\HttpCode;
use common\models\AR\MenuItem;
use common\models\form\MenuItemForm;
use common\models\form\MenuItemImageUploadForm;
use common\services\MenuItemService;
use Throwable;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\User;

class MenuController extends AppController
{
    private MenuItemService $service;

    /**
     * @param string $id
     * @param Module $module
     * @param User $user
     * @param MenuItemService $service
     * @param array $config
     */
    public function __construct(string $id, Module $module, User $user, MenuItemService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $user, $config);
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
            ->notDeleted()
            ->with(['image', 'category']);

        return new ActiveDataProvider([
           'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
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
        return $this->findModel($id, ['image']);
    }

    /**
     * @param int $id
     * @param array $with
     * @return MenuItem
     * @throws NotFoundHttpException
     */
    private function findModel(int $id, array $with = []): MenuItem
    {
        if ($model = MenuItem::find()
            ->byId($id)
            ->byUserId($this->user->getId())
            ->notDeleted()
            ->with($with)
            ->one()
        ) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}