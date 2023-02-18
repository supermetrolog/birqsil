<?php

namespace common\tests\unit\models;

use Yii;
use common\models\user\LoginForm;
use common\fixtures\UserFixture;

/**
 * Login form test
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    /**
     * @return array<string,array<string,string>>
     */
    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    public function testLoginNoUser(): void
    {
        $model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        verify($model->login())->false();
        verify(Yii::$app->user->isGuest)->true();
    }

    public function testLoginWrongPassword(): void
    {
        $model = new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'wrong_password',
        ]);

        verify($model->login())->false();
        verify($model->errors)->arrayHasKey('password');
        verify(Yii::$app->user->isGuest)->true();
    }

    //     public function testLoginCorrect()
    //     {
    //         $model = new LoginForm([
    //             'username' => 'bayer.hudson',
    //             'password' => 'password_0',
    //         ]);

    //         verify($model->login())->true();
    //         verify($model->errors)->arrayHasNotKey('password');
    //         verify(Yii::$app->user->isGuest)->false();
    //     }
}
