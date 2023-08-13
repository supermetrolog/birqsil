<?php

namespace frontend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\RestaurantFixture;
use common\helpers\HttpCode;

class RestaurantCest
{
    public function _before(ApiTester $I): void
    {
        $I->haveFixtures([
            'restaurant' => [
                'class' => RestaurantFixture::class,
                'dataFile' => codecept_data_dir('restaurant.php')
            ]
        ]);
    }

    public function viewByUniqueName(ApiTester $I): void
    {
        $I->sendGet('/restaurant/unique/22222');
        $I->seeResponseCodeIs(HttpCode::OK->value);
    }
}