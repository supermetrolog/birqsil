<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\helpers\HttpCode;

class SiteCest
{
//    public function _before(ApiTester $I): void
//    {
//        $I->haveFixtures([
//            'user' => [
//                'class' => UserFixture::class,
//                'dataFile' => codecept_data_dir() . 'user.php'
//            ]
//        ]);
//    }

    public function signUpBadMethod(ApiTester $I): void
    {
        $I->sendGet('/signup');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND->value);
        $I->seeResponseIsJson();
    }

//    public function signUpValid(ApiTester $I): void
//    {
//        $I->sendPost('/signup', [
//            'email' => 'fuck@suck.ru',
//            'password' => 'nigga'
//        ]);
//        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED->value);
//        $I->seeResponseIsJson();
//    }
}