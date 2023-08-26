<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\helpers\HttpCode;

class UnitCest extends Auth
{
    public function update(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendGet('/unit');

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }
}