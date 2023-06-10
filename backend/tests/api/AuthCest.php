<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\UserFixture;
use common\helpers\HttpCode;

class AuthCest
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
                'expire' => 'integer',
                'token' => 'string',
            ]
        );

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function signIn(ApiTester $I): void
    {
        $I->sendPost('/signin', [
            'email' => 'test@test.test',
            'password' => 'password_0',
        ]);

        $I->seeResponseMatchesJsonType(
            [
                'expire' => 'integer',
                'token' => 'string',
            ]
        );

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function verifyEmail(ApiTester $I): void
    {
        $I->sendGet('/verify-email', [
            'token' => 'valid'
        ]);

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
    }

    public function resetPassword(ApiTester $I): void
    {
        $I->sendPost('/reset-password', [
            'token' => 'RkD_Jw0_8HEedzLk7MM-ZKEFfYR7VbMr_2682194998',
            'password' => 'newPassword'
        ]);

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
    }

    public function resetPasswordRequest(ApiTester $I): void
    {
        $I->sendPost('/reset-password-request', [
            'email' => 'test2@test.test',
        ]);

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
    }
}