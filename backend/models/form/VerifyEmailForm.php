<?php

namespace backend\models\form;

use common\base\exception\ValidateException;
use common\base\model\Form;
use common\enums\UserStatus;
use common\models\AR\User;

class VerifyEmailForm extends Form
{
    public string|null $token = null;

    private User|null $user;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['token', 'required'],
            ['token', 'string', 'max' => 255],
            ['token', 'validateToken']
        ];
    }

    /**
     * @param string $attr
     * @return void
     */
    public function validateToken(string $attr): void
    {
        $this->user = User::findByVerificationToken($this->token);
        if (!$this->user) {
            $this->addError($attr, 'Invalid token');
        }
    }

    /**
     * @return void
     * @throws ValidateException
     */
    public function verify(): void
    {
        $this->ifNotValidThrow();

        $this->user->status = UserStatus::Active->value;
        $this->user->saveOrThrow();
    }
}