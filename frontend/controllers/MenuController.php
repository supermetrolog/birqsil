<?php

namespace frontend\controllers;

use common\models\AR\MenuItem;
use yii\data\ActiveDataProvider;

class MenuController extends AppController
{
    /**
     * @param string $restaurant_unique_name
     * @return ActiveDataProvider
     */
    public function actionIndex(string $restaurant_unique_name): ActiveDataProvider
    {
        $query = MenuItem::find()
            ->byRestaurantUniqueName($restaurant_unique_name)
            ->notDeleted()
            ->with(['image']);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);
    }
}