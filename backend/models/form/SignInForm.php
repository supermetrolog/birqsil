<?php

namespace backend\models\form;

use common\base\model\Form;
use common\models\AR\User;

class SignInForm extends Form
{
    public string|null $email = null;
    public string|null $password = null;

    private User|null $user = null;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['password', 'validatePassword']
        ];
    }

    /**
     * @param string $attribute
     * @return void
     */
    public function validatePassword(string $attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * @return User|null
     */
    public function getUser(): User|null
    {
        if (!$this->user) {
            $this->user = User::findByEmail($this->email);
        }

        return $this->user;
    }
}