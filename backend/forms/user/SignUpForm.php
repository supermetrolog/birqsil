<?php

declare(strict_types=1);

namespace app\forms\user;

use common\forms\base\CompositeForm;

class SignUpForm extends CompositeForm
{
    public UsernameForm $username;
    /** @var string */
    public $firstName;
    /** @var string|null */
    public $email;

    public function __construct(array $config = [])
    {
        $this->username = new UsernameForm();
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['firstName', 'required'],
            [['firstName', 'email'], 'string', 'max' => 255, 'min' => 2]
        ];
    }

    protected function internalForms(): array
    {
        return ['username'];
    }
}
