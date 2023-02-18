<?php

declare(strict_types=1);

namespace app\tests\unit\forms\user;

use app\forms\user\SignUp;
use Codeception\Test\Unit;
use common\models\user\User;

class SignUpTest extends Unit
{
    public function testSignUpCorrect()
    {
        $model = new SignUp([
            'username' => 'nigga',
            'password' => 'password_0',
            'email' => 'nigga@mail.com'
        ]);

        $user = $model->signup();
        $userFromDB = User::findByUsername('nigga');
        verify($userFromDB)->notNull()->notEmpty();
        verify($user)->instanceOf(User::class);
        verify($user->getAttributes())->equals($userFromDB->getAttributes());

        verify($user->auth_key)->notEmpty();
        verify($user->validatePassword('password_0'))->true();
    }
}
