<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\MenuItemFixture;
use common\helpers\HttpCode;

class MenuCest extends Auth
{
    public function _before(ApiTester $I): void
    {
        parent::_before($I);

        $I->haveFixtures([
            'menu_item' => [
                'class' => MenuItemFixture::class,
                'dataFile' => codecept_data_dir() . 'menu_item.php'
            ]
        ]);
    }

    public function create(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPost('/menu', [
            'restaurant_id' => 1,
            'title' => 'Test',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function update(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPut('/menu/1', [
            'title' => 'Test2',
            'description' => 'Test2',
        ]);

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
    }

    public function delete(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendDelete('/menu/1');

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param ApiTester $I
     * @return void
     */
    public function index(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendGet('/menu/2');
        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function uploadFile(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPost(
            'menu/1/upload-files',
            [],
            ['images' => codecept_data_dir('JPEG-FILE.jpeg')]
        );

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
    }
}