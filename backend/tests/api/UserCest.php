<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\UserFixture;
use common\helpers\HttpCode;

class UserCest
{
    public function _before(ApiTester $I): void
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
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
}