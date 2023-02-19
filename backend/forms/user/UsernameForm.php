<?php

declare(strict_types=1);

namespace app\forms\user;

use common\models\user\User;
use common\models\user\UsernameType;
use common\validators\RuPhoneValidator;
use yii\base\Model;
use yii\validators\EmailValidator;

/**
 * Username can be Phone Number or Email
 */
class UsernameForm extends Model
{
    /** @var string */
    public $username = '';

    public function rules(): array
    {
        return [
            ['username', 'required'],
            ['username', 'trim'],
            ['username', 'unique', 'targetClass' => User::class],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'validateUsername']
        ];
    }
    public function validateUsername($attribute): void
    {
        $validator = new RuPhoneValidator();
        if (!is_numeric($this->username)) {
            $validator = new EmailValidator();
        }
        if (!$validator->validate($this->username)) {
            $this->addError($attribute, "Username is invalid.");
        }
    }
    public function getType(): UsernameType
    {
        if (is_numeric($this->username)) {
            return UsernameType::Phone;
        }
        return UsernameType::Email;
    }

    public function set(string $value): void
    {
        $this->username = $value;
    }
    public function get(): string
    {
        return $this->username;
    }
}
