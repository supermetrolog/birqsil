<?php

namespace backend\controllers;

use backend\models\form\RestaurantForm;
use common\base\exception\ValidateException;
use common\models\AR\Restaurant;
use common\models\AR\User;
use Yii;
use yii\web\NotFoundHttpException;

class RestaurantController extends AppController
{
    private User|null $identity;

    public function __construct($id, $module, User|null $identity, $config = [])
    {
        $this->identity = $identity;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return Restaurant
     * @throws ValidateException
     */
    public function actionCreate(): Restaurant
    {
        return new Restaurant();
        $form = new RestaurantForm();
        $form->setScenario(RestaurantForm::SCENARIO_CREATE);

        $form->load($this->request->post());
        Yii::warning($this->identity->id);
        $form->user_id = $this->identity->id;
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