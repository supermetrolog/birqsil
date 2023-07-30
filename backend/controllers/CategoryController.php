<?php

namespace backend\controllers;

use backend\models\form\CategoryForm;
use common\base\exception\ValidateException;
use common\helpers\HttpCode;
use common\models\AR\Category;
use Exception;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class CategoryController extends AppController
{
    /**
     * @param int $restaurant_id
     * @return ActiveDataProvider
     */
    public function actionIndex(int $restaurant_id): ActiveDataProvider
    {
        $query = Category::find()
            ->byUserId($this->user->getId())
            ->byRestaurantId($restaurant_id);

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
     * @return Category
     * @throws ValidateException
     * @throws Exception
     */
    public function actionCreate(): Category
    {
        $form = new CategoryForm();
        $form->setScenario(CategoryForm::SCENARIO_CREATE);

        $form->load($this->request->post());

        return $form->create();
    }


    /**
     * @param int $id
     * @return Category
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function actionUpdate(int $id): Category
    {
        $model = $this->findModel($id);

        $form = new CategoryForm();
        $form->setScenario(CategoryForm::SCENARIO_UPDATE);

        $form->load($this->request->post());
        $form->id = $id;
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
        $this->findModel($id)->delete();
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return Category
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Category
    {
        $query = Category::find()->byID($id);

        if (!$this->user->isGuest) {
            $query->byUserID($this->user->id);
        }

        if ($model = $query->one()) {
            return $model;
        }

        throw new NotFoundHttpException('Category not found');
    }
}