<?php

namespace backend\controllers;

use backend\models\form\RestaurantForm;
use common\base\exception\ValidateException;
use common\models\AR\Restaurant;
use yii\web\NotFoundHttpException;
use yii\web\User;

class RestaurantController extends AppController
{
    private User|null $user;

    /**
     * @param $id
     * @param $module
     * @param User|null $user
     * @param array $config
     */
    public function __construct($id, $module, User|null $user, array $config = [])
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
        $model = Restaurant::find()->byID($id)->one();
        if (!$model) {
            throw new NotFoundHttpException('Model not found');
        }

        $form = new RestaurantForm();
        $form->load($this->request->post());
        $form->update($model);
        return $model;
    }
}