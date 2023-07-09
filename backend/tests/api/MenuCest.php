<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\helpers\HttpCode;

class MenuCest extends Auth
{
    public function create(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPost('/menu', [
            'restaurant_id' => 1,
            'title' => 'Test',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }
}