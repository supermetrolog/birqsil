<?php

namespace common\tests\unit\services;

use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\enums\RestaurantStatus;
use common\fixtures\RestaurantFixture;
use common\fixtures\UserFixture;
use common\models\AR\Restaurant;
use common\services\RestaurantService;
use Yii;

class RestaurantServiceTest extends Unit
{
    public function _fixtures(): array
    {
        return [
            'restaurant' => [
                'class' => RestaurantFixture::class,
                'dataFile' => codecept_data_dir() . 'restaurant.php'
            ],
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ];
    }

    /**
     * @return RestaurantService
     */
    private function getService(): RestaurantService
    {
        return new RestaurantService(Yii::$app->db);
    }

    public function testDelete(): void
    {
        $service = $this->getService();
        $model = Restaurant::find()->active()->one();

        verify($model->deleted_at)->null();
        verify($model->status)->equals(RestaurantStatus::HIDDEN->value);

        try {
            $service->delete($model);
        } catch (ValidateException $th) {
            codecept_debug($th->getModel()->getErrors());
            throw $th;
        }

        $model = Restaurant::find()->byID($model->id)->one();
        verify($model->deleted_at)->notNull();
        verify($model->status)->equals(RestaurantStatus::DELETED->value);
    }
}