<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\UserAccessTokenFixture;
use common\fixtures\UserFixture;
use common\helpers\HttpCode;
use common\models\AR\UserAccessToken;

class UserCest
{
    private UserAccessToken $accessToken;
    public function _before(ApiTester $I): void
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'user_access_token' => [
                'class' => UserAccessTokenFixture::class,
                'dataFile' => codecept_data_dir() . 'user_access_token.php'
            ],
        ]);

        $this->accessToken = UserAccessToken::find()->one();
    }

    public function checkEmailExists(ApiTester $I): void
    {
        $I->sendGet('/user/check-email-exists', [
            'email' => 'test2@test.test',
        ]);

        $I->seeResponseMatchesJsonType(
            [
                'exists' => 'boolean',
            ]
        );

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function findByToken(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendGet('/user/find-by-token/valid_token');
        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    private function auth(ApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', 'Bearer ' . $this->accessToken->token);
    }
}