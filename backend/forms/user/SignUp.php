<?php

declare(strict_types=1);

namespace app\forms\user;

use common\forms\exceptions\ValidateException;
use common\models\user\User;
use yii\base\Model;

class SignUp extends Model
{
    public string $username = '';
    public string $password = '';
    public string $email = '';

    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required']
        ];
    }
    public function signup(): User
    {
        if (!$this->validate()) {
            throw new ValidateException($this);
        }
        $user = new User();
        $user->loadDefaultValues();
        $user->generateAuthKey();
        $user->setPassword($this->password);
        $user->username = $this->username;
        $user->email = $this->email;

        $user->saveOrThrow();
        return $user;
    }
}
