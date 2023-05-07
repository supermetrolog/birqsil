<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\UserFixture;
use common\helpers\HttpCode;

class SiteCest
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

    public function signUp(ApiTester $I): void
    {
        $I->sendPost('/signup', [
            'email' => 'fuc2@suck.ru',
            'password' => '12345678',
            'passwordRepeat' => '12345678'
        ]);

        $I->seeResponseMatchesJsonType(
            [
                'expire'       => 'integer',
                'token'       => 'string',
            ]
        );

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }
}