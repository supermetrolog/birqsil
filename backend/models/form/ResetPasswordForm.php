<?php

namespace backend\models\form;

use common\base\exception\ValidateException;
use common\base\model\Form;
use common\enums\AppParams;
use common\models\AR\User;
use Exception;
use Yii;

class ResetPasswordForm extends Form
{
    public string|null $token = null;

    public string|null $password = null;

    private User|null $user;


    /**
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            ['token', 'required'],
            ['token', 'string', 'max' => 255],
            ['token', 'validateToken'],
            ['password', 'required'],
            [
                'password',
                'string',
                'min' => Yii::$app->param->get(AppParams::USER_PASSWORD_MIN),
                'max' => Yii::$app->param->get(AppParams::USER_PASSWORD_MAX),
            ],
        ];
    }

    /**
     * @param string $attr
     * @return void
     */
    public function validateToken(string $attr): void
    {
        $this->user = User::findByPasswordResetToken($this->token);
        if (!$this->user) {
            $this->addError($attr, 'Invalid token');
        }
    }


    /**
     * @return void
     * @throws ValidateException
     * @throws \yii\base\Exception
     */
    public function reset(): void
    {
        $this->ifNotValidThrow();

        $this->user->setPassword($this->password);
        $this->user->removePasswordResetToken();
        $this->user->generateAuthKey();

        $this->user->saveOrThrow();
    }
}