<?php

declare(strict_types=1);

namespace app\models\forms\user;

use common\base\form\CompositeForm;

class SignUpCodeForm extends CompositeForm
{
    /** @var int */
    public $code;
    public UsernameForm $username;

    public function __construct(array $config = [])
    {
        $this->username = new UsernameForm();

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['code', 'required'],
            ['code', 'integer', 'min' => 100000, 'max' => 999999]
        ];
    }

    public function internalForms(): array
    {
        return ['username'];
    }
}
