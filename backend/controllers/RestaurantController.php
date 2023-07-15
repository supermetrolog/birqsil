<?php

namespace backend\controllers;

use backend\models\form\RestaurantForm;
use common\base\exception\ValidateException;
use common\enums\RestaurantStatus;
use common\helpers\HttpCode;
use common\models\AR\Restaurant;
use common\services\RestaurantService;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class RestaurantController extends AppController
{
    private User $user;
    private RestaurantService $service;

    /**
     * @param $id
     * @param $module
     * @param User $user
     * @param RestaurantService $service
     * @param array $config
     */
    public function __construct($id, $module, User $user, RestaurantService $service, array $config = [])
    {
        $this->user = $user;
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return ActiveDataProvider
     */
    public function actionIndex(): ActiveDataProvider
    {
        $query = Restaurant::find()->byUserID($this->user->id)->active();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return Restaurant
     * @throws ValidateException
     */
    public function actionCreate(): Restaurant
    {
        $form = new RestaurantForm();
        $form->setScenario(RestaurantForm::SCENARIO_CREATE);

        $form->load($this->request->post());
        $form->user_id = $this->user->identity->id;
        return $form->create();
    }


    /**
     * @param int $id
     * @return Restaurant
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function actionUpdate(int $id): Restaurant
    {
        $model = $this->findModel($id);

        $form = new RestaurantForm();
        $form->setScenario(RestaurantForm::SCENARIO_UPDATE);

        $form->load($this->request->post());
        $form->update($model);
        return $model;
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): void
    {
        $this->service->delete($this->findModel($id));
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return Restaurant
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): Restaurant
    {
        return $this->findModel($id);
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function actionPublish(int $id): void
    {
        $model = $this->findModel($id);
        $model->setStatus(RestaurantStatus::PUBLISHED);
        $model->saveOrThrow();

        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function actionHide(int $id): void
    {
        $model = $this->findModel($id);
        $model->setStatus(RestaurantStatus::HIDDEN);
        $model->saveOrThrow();

        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }
    /**
     * @param int $id
     * @return Restaurant
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Restaurant
    {
        if ($model = Restaurant::find()->byID($id)->notDeleted()->one()) {
            return $model;
        }

        throw new NotFoundHttpException('Restaurant not found');
    }
}