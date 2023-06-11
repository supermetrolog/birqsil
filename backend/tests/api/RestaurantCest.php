<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\RestaurantFixture;
use common\fixtures\UserAccessTokenFixture;
use common\fixtures\UserFixture;
use common\helpers\HttpCode;
use common\models\AR\UserAccessToken;

class RestaurantCest
{
    private UserAccessToken $accessToken;

    public function _before(ApiTester $I): void
    {
        $I->haveFixtures([
            'restaurant' => [
                'class' => RestaurantFixture::class,
                'dataFile' => codecept_data_dir() . 'restaurant.php'
            ],
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'user_access_token' => [
                'class' => UserAccessTokenFixture::class,
                'dataFile' => codecept_data_dir() . 'user_access_token.php'
            ]
        ]);

        $this->accessToken = UserAccessToken::find()->one();
    }

    private function auth(ApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->accessToken->token);
    }
    public function create(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPost('/restaurant', [
            'name' => 'Restaurant name',
            'legalName' => 'Restaurant legal name',
            'address' => 'Moscow, Lenina street'
        ]);

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function update(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPut('/restaurant/1', [
            'name' => 'Restaurant name',
            'legalName' => 'Restaurant legal name',
            'address' => 'Moscow, Lenina street'
        ]);

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }
}