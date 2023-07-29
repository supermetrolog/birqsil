<?php

namespace frontend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\UserAccessTokenFixture;
use common\fixtures\UserFixture;
use common\helpers\HttpCode;
use common\models\AR\UserAccessToken;

class SiteCest
{
    public function index(ApiTester $I): void
    {
        $I->sendGet('');
        $I->seeResponseCodeIs(HttpCode::OK->value);
    }
}