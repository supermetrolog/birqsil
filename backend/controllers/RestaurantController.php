<?php

namespace backend\controllers;

use backend\models\form\RestaurantForm;
use common\base\exception\ValidateException;
use common\helpers\HttpCode;
use common\models\AR\Restaurant;
use Throwable;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class RestaurantController extends AppController
{
    private User $user;

    /**
     * @param $id
     * @param $module
     * @param User $user
     * @param array $config
     */
    public function __construct($id, $module, User $user, array $config = [])
    {
        $this->user = $user;
        parent::__construct($id, $module, $config);
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
        $model = $this->findModel($id);
        $model->delete();

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
     * @return Restaurant
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Restaurant
    {
        if ($model = Restaurant::find()->byID($id)->one()) {
            return $model;
        }

        throw new NotFoundHttpException('Restaurant not found');
    }
}