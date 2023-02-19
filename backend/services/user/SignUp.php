<?php

declare(strict_types=1);

namespace app\services\user;

use app\forms\user\SignUpCodeForm;
use Exception;
use app\forms\user\SignUpForm;
use common\components\interfaces\CodeGeneratorInterface;
use yii\caching\CacheInterface;
use common\models\user\UsernameType;
use common\forms\exceptions\ValidateException;
use common\components\interfaces\SenderInterface;
use common\repositories\interfaces\UserRepositoryInterface;
use LogicException;

class SignUp
{
    public const CODE_LENGTH_MIN = 6;
    public const CODE_LENGTH_MAX = 6;

    public function __construct(
        private SenderInterface $sender,
        private CodeGeneratorInterface $codeGenerator,
        private CacheInterface $cache,
        private UserRepositoryInterface $userRepo,
        // time of wait yet user sent code 
        private int $expire = 180,
    ) {
    }

    public function sendCode(SignUpForm $form): bool
    {
        if (!$form->validate()) {
            throw new ValidateException($form);
        }
        if ($this->cache->exists($form->username->get())) {
            throw new LogicException($this->expire . 'seconds have not passed');
        }

        $code = $this->codeGenerator->generate(self::CODE_LENGTH_MIN, self::CODE_LENGTH_MAX);

        $cacheData = [
            'form' => $form,
            'code' => $code
        ];
        $this->cache->set($form->username->get(), $cacheData, $this->expire);
        if ($form->username->getType() === UsernameType::Email) {
            return $this->sender->email($form->username->get(), (string) $code);
        }
        if ($form->username->getType() === UsernameType::Phone) {
            return $this->sender->sms($form->username->get(), (string) $code);
        }

        throw new Exception('unknown username type');
    }

    public function validateCode(SignUpCodeForm $form)
    {
        if (!$form->validate()) {
            throw new ValidateException($form);
        }
        if (!$this->cache->exists($form->username->get())) {
            throw new LogicException($this->expire . 'seconds have not passed');
        }
    }
}
