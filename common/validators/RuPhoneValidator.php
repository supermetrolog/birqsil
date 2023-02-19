<?php

declare(strict_types=1);

namespace common\validators;

use yii\validators\Validator;

class RuPhoneValidator extends Validator
{
    public function init(): void
    {
        $this->message = '{attribute} is not a valid phone';
    }
    protected function validateValue($value): array|null
    {
        $invalid = [$this->message, []];
        if (
            $value === null ||
            !is_numeric($value) ||
            strlen($value) !== 11 ||
            $value[0] !== '7'
        ) {
            return $invalid;
        }

        return null;
    }
}
