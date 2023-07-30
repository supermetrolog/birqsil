<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\CategoryFixture;
use common\fixtures\RestaurantFixture;
use common\fixtures\UserAccessTokenFixture;
use common\fixtures\UserFixture;
use common\helpers\HttpCode;
use common\models\AR\UserAccessToken;

class CategoryCest
{
    private UserAccessToken $accessToken;

    public function _before(ApiTester $I): void
    {
        $I->haveFixtures([
            'category' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir('category.php')
            ],
            'restaurant' => [
                'class' => RestaurantFixture::class,
                'dataFile' => codecept_data_dir('restaurant.php')
            ],
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir('user.php')
            ],
            'user_access_token' => [
                'class' => UserAccessTokenFixture::class,
                'dataFile' => codecept_data_dir('user_access_token.php')
            ]
        ]);

        $this->accessToken = UserAccessToken::find()->one();
    }


    private function auth(ApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->accessToken->token);
    }

    /**
     * @param ApiTester $I
     * @return void
     */
    public function index(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendGet('/category/1');
        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function create(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPost('/category', [
            'restaurant_id' => 1,
            'name' => 'Test313',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function update(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPut('/category/2', [
            'name' => 'TestTEstsda',
            'restaurant_id' => 2,
        ]);

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function delete(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendDelete('/category/2');

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
    }
}