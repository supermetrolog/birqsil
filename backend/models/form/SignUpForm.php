<?php

namespace backend\models\form;

use common\base\model\Form;
use common\models\AR\User;
use Yii;

class SignUpForm extends Form
{
    public string|null $email = null;
    public string|null $password = null;
    public string|null $passwordRepeat = null;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                'email',
                'string',
                'max' => Yii::$app->params['user.emailMax'],
                'min' => Yii::$app->params['user.emailMin']
            ],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            [
                'password',
                'string',
                'max' => Yii::$app->params['user.passwordMax'],
                'min' => Yii::$app->params['user.passwordMin']
            ],
            [['email', 'password', 'passwordRepeat'], 'required'],
            [['email', 'password', 'passwordRepeat'], 'string'],
            ['passwordRepeat', 'compare','compareAttribute'=>'password']
        ];
    }
}