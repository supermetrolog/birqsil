<?php

namespace app\models\form;

use common\base\exception\ValidateException;
use common\base\interfaces\notifier\NotifierInterface;
use common\base\model\Form;
use common\models\AR\User;
use common\notifications\VerifyEmailNotification;
use Yii;
use yii\base\Exception;

class SignUpForm extends Form
{
    public string|null $email = null;
    public string|null $password = null;
    public string|null $passwordRepeat = null;

    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier, $config = [])
    {
        parent::__construct($config);
        $this->notifier = $notifier;
    }

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

    /**
     * @return void
     * @throws ValidateException
     * @throws Exception
     */
    public function signUp(): void
    {
        if (!$this->validate()){
            throw new ValidateException($this);
        }

        $user = new User();
        $user->email = $this->email;
        $user->generateAuthKey();
        $user->generatePasswordResetToken();
        $user->generateVerificationToken();
        $user->setPassword($this->password);

        $user->saveOrThrow();

        $this->notifier->notify($user, new VerifyEmailNotification(['token' => $user->verification_token]));
    }
}