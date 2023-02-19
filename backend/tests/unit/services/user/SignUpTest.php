<?php

declare(strict_types=1);

namespace app\tests\unit\services\user;

use app\forms\user\SignUpCodeForm;
use app\forms\user\SignUpForm;
use app\services\user\SignUp;
use Codeception\Test\Unit;
use common\components\interfaces\CodeGeneratorInterface;
use common\components\interfaces\SenderInterface;
use common\forms\exceptions\ValidateException;
use common\repositories\interfaces\UserRepositoryInterface;
use Exception;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use yii\caching\CacheInterface;

class SignUpTest extends Unit
{
    private SignUpForm $form;
    private SignUpCodeForm $codeForm;
    private CacheInterface $cache;
    private SenderInterface $sender;
    private CodeGeneratorInterface $codeGenerator;
    private UserRepositoryInterface $userRepo;

    private SignUp $service;

    public function _before(): void
    {
        $this->form = new SignUpForm();
        $this->codeForm = new SignUpCodeForm();
        $this->cache = $this->createMock(CacheInterface::class);
        $this->sender = $this->createMock(SenderInterface::class);
        $this->codeGenerator = $this->createMock(CodeGeneratorInterface::class);
        $this->userRepo = $this->createMock(UserRepositoryInterface::class);

        $this->service = new SignUp(
            $this->sender,
            $this->codeGenerator,
            $this->cache,
            $this->userRepo
        );
    }

    public function testSendCodeWithInvalidForm(): void
    {
        $this->form->username->set('');
        $this->expectException(ValidateException::class);
        $this->service->sendCode($this->form);
    }

    public function testSendCodeWithDataAlreadyExistInCache(): void
    {
        /** @var MockObject */
        $cache = $this->cache;
        $this->form->username->set('79123332211');
        $this->form->firstName = 'nigger';
        $cache->method('exists')->with('79123332211')->willReturn(true);
        $this->expectException(LogicException::class);
        $this->service->sendCode($this->form);
    }

    public function testSendCodeValid(): void
    {
        /** @var MockObject */
        $cache = $this->cache;
        /** @var MockObject */
        $codeGenerator = $this->codeGenerator;
        /** @var MockObject */
        $sender = $this->sender;

        $code = 123456;

        $this->form->username->set('79123332211');
        $this->form->firstName = 'name';
        $cache->method('exists')->with('79123332211')->willReturn(false);
        $codeGenerator
            ->method('generate')
            ->with(
                SignUp::CODE_LENGTH_MIN,
                SignUp::CODE_LENGTH_MAX
            )
            ->willReturn($code);
        $cache->method('set')->with(
            $this->form->username->get(),
            ['form' => $this->form, 'code' => $code],
            180
        );
        $sender->method('email')->with($this->form->username->get(), $code);

        $this->service->sendCode($this->form);
    }

    public function testValidateCodeWithotCodeInCache(): void
    {
        /** @var MockObject */
        $cache = $this->cache;

        $code = 123456;

        $this->codeForm->load(
            [
                'code' => $code,
                'username' => 'nigger@mail.ru'
            ],
            ''
        );

        $cache->method('exists')->with($this->codeForm->username->get())->willReturn(false);

        $this->expectException(LogicException::class);
        $this->service->validateCode($this->codeForm);
    }
}
