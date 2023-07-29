<?php

namespace frontend\tests\api;

use common\fixtures\RestaurantFixture;
use common\helpers\HttpCode;
use common\models\AR\Restaurant;
use frontend\tests\ApiTester;

class MenuCest
{
    private string $restaurantUniqueName;

    public function _before(ApiTester $I): void
    {
        $I->haveFixtures([
           'restaurant' => [
               'class' => RestaurantFixture::class,
               'dataFile' => codecept_data_dir('restaurant.php')
           ]
        ]);

        $this->restaurantUniqueName = Restaurant::find()->one()->unique_name;
    }
    public function index(ApiTester $I): void
    {
        $I->sendGet('menu/' . $this->restaurantUniqueName);
        $I->seeResponseCodeIs(HttpCode::OK->value);
    }
}