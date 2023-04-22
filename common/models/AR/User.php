<?php

namespace common\models\AR;

use common\base\model\AR;
use common\enums\AppParams;
use common\enums\UserStatus;
use common\models\AQ\UserQuery;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $verification_token
 */
class User extends AR implements IdentityInterface
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * @return string[]
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['email', 'verification_token', 'password_hash'], 'required'],
            [['email', 'verification_token', 'password_reset_token'], 'string'],
            ['email', 'unique'],
            ['verification_token', 'unique'],
            ['password_reset_token', 'unique'],
            ['status', 'default', 'value' => UserStatus::Inactive->value],
            ['status', 'in', 'range' => [
                UserStatus::Active->value,
                UserStatus::Inactive->value,
                UserStatus::Deleted->value
            ]],
        ];
    }

    /**
     * @param $id
     * @return static|null
     */
    public static function findIdentity($id): ?static
    {
        return static::findOne(['id' => $id, 'status' => UserStatus::Active->value]);
    }

    /**
     * @param $token
     * @param $type
     * @return $this|null
     */
    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): ?static
    {
        return static::findOne(['username' => $username, 'status' => UserStatus::Active->value]);
    }

    /**
     * @param string $token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): ?static
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => UserStatus::Active->value,
        ]);
    }

    /**
     * @param string $token
     * @return static|null
     */
    public static function findByVerificationToken(string $token): ?static
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => UserStatus::Inactive->value
        ]);
    }

    /**
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->param->get(AppParams::USER_PASSWORD_RESET_TOKEN_EXPIRE);
        return $timestamp + $expire >= time();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * @param $authKey
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param string $password
     * @return void
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function generateVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @return void
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    /**
     * @return UserQuery
     */
    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }
}
