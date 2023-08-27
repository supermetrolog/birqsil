<?php

namespace frontend\controllers;

use common\models\AQ\CategoryQuery;
use common\models\AQ\MenuItemQuery;
use common\models\AR\Restaurant;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class RestaurantController extends AppController
{
    /**
     * @param string $unique_name
     * @return Restaurant
     * @throws NotFoundHttpException
     */
    public function actionUniqueView(string $unique_name): Restaurant
    {
        $model = Restaurant::find()
            ->byUniqueName($unique_name)
            ->published()
            ->with([
                'categories' => function (CategoryQuery $query) {
                    $query->orderByOrdering(SORT_ASC);
                },
                'categories.menuItems' => function (MenuItemQuery $query) {
                    $query->orderByOrdering(SORT_ASC);
                },
                'categories.menuItems.image',
                'categories.menuItems.unit'
            ])
            ->one();

        if (!$model) {
            throw new NotFoundHttpException('Restaurant not found');
        }

        return $model->setQrCodeLink(sprintf('%s/v1/restaurant/%d/qrcode', Url::base(true), $model->id));
    }
}