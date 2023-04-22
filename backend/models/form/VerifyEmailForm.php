<?php

namespace app\models\form;

use common\base\exception\ValidateException;
use common\base\model\Form;
use common\enums\UserStatus;
use common\models\AR\User;

class VerifyEmailForm extends Form
{
    public string|null $token = null;

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
        if (!User::findByVerificationToken($this->token)) {
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

        $user = User::findByVerificationToken($this->token);
        $user->status = UserStatus::Active->value;
        $user->saveOrThrow();
    }
}