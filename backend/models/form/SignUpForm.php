<?php

namespace app\models\form;

use common\base\exception\ValidateException;
use common\base\interfaces\notifier\NotifierInterface;
use common\base\model\Form;
use common\models\AR\User;
use common\notifications\VerifyEmailNotification;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\db\Connection;

class SignUpForm extends Form
{
    public string|null $email = null;
    public string|null $password = null;
    public string|null $passwordRepeat = null;

    private NotifierInterface $notifier;
    private SignInForm $signInForm;

    private Connection $db;

    public function __construct(
        NotifierInterface $notifier,
        SignInForm $signInForm,
        Connection $db,
        $config = [])
    {
        parent::__construct($config);
        $this->notifier = $notifier;
        $this->signInForm = $signInForm;
        $this->db = $db;
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
     * @return string
     * @throws Exception
     * @throws Throwable
     * @throws ValidateException
     * @throws \yii\db\Exception
     */
    public function signUp(): string
    {
        if (!$this->validate()){
            throw new ValidateException($this);
        }

        $tx = $this->db->beginTransaction();
        try {
            $user = new User();
            $user->email = $this->email;
            $user->generateAuthKey();
            $user->generatePasswordResetToken();
            $user->generateVerificationToken();
            $user->setPassword($this->password);

            $user->saveOrThrow();

            $this->signInForm->load([
                $user->email,
                $this->password
            ]);

            $accessToken = $this->signInForm->signIn();

            $this->notifier->notify($user, new VerifyEmailNotification(['token' => $user->verification_token]));

            $tx->commit();
            return $accessToken;
        } catch (Throwable $th) {
            $tx->rollBack();
            throw $th;
        }
    }
}