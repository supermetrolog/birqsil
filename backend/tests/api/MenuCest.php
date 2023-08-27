<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\enums\AppParams;
use common\fixtures\CategoryFixture;
use common\fixtures\MenuItemFixture;
use common\helpers\HttpCode;
use common\models\AR\Category;
use common\models\AR\MenuItem;
use common\models\AR\Restaurant;
use Yii;

class MenuCest extends Auth
{
    public function _before(ApiTester $I): void
    {
        parent::_before($I);

        $I->haveFixtures([
            'menu_item' => [
                'class' => MenuItemFixture::class,
                'dataFile' => codecept_data_dir() . 'menu_item.php'
            ],
            'category' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir('category.php')
            ],
        ]);
    }

    public function create(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPost('/menu', [
            'restaurant_id' => 1,
            'price' => 125,
            'title' => 'Test',
            'category_id' => 1
        ]);

        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function update(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPut('/menu/1', [
            'restaurant_id' => 2,
            'title' => 'Test2',
            'price' => 125,
            'description' => 'Test2',
            'category_id' => 2
        ]);

        $I->seeResponseCodeIs(HttpCode::OK->value);
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
            'menu/1/upload-file',
            [],
            ['image' => codecept_data_dir('JPEG-FILE.jpeg')]
        );

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
        array_map('unlink', array_filter(
            (array) array_merge(glob(Yii::$app->param->get(AppParams::UPLOAD_FILE_BASE_PATH) . "*"))));
    }

    /**
     * @param ApiTester $I
     * @return void
     */
    public function view(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendGet('/menu/item/1');
        $I->seeResponseCodeIs(HttpCode::OK->value);
    }

    public function orderAfterGreatestCurrent(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPost('/menu/order', [
            'current_id' => 2,
            'after_id' => 3,
        ]);

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);

        $I->seeRecord(MenuItem::class, [
            'id' => 2,
            'ordering' => 3,
        ]);
    }

    public function orderAfterLeastCurrent(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPost('/menu/order', [
            'current_id' => 3,
            'after_id' => 2,
        ]);

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
        $I->seeRecord(MenuItem::class, [
            'id' => 3,
            'ordering' => 2,
        ]);

        $I->seeRecord(MenuItem::class, [
            'id' => 2,
            'ordering' => 3,
        ]);
    }

    public function orderAfterNull(ApiTester $I): void
    {
        $this->auth($I);
        $I->sendPost('/menu/order', [
            'current_id' => 2,
            'after_id' => null,
        ]);

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT->value);
        $I->seeRecord(MenuItem::class, [
            'id' => 2,
            'ordering' => 4,
        ]);
    }
}