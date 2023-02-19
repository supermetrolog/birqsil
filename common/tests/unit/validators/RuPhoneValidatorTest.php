<?php

declare(strict_types=1);

namespace common\tests\unit\validators;

use Codeception\Test\Unit;
use common\validators\RuPhoneValidator;

class RuPhoneValidatorTest extends Unit
{
    public function testValidate(): void
    {
        $testCases = [
            [
                'phone' => '79216667272',
                'expected' => true,
            ],
            [
                'phone' => '792166672723',
                'expected' => false,
            ],
            [
                'phone' => '79s216667272',
                'expected' => false,
            ],
            [
                'phone' => '29216667272',
                'expected' => false,
            ],
            [
                'phone' => '9216667272',
                'expected' => false,
            ],
            [
                'phone' => '70000000000',
                'expected' => true,
            ],
        ];

        foreach ($testCases as $tc) {
            $validator = new RuPhoneValidator();
            verify($validator->validate($tc['phone']))
                ->equals(
                    $tc['expected'],
                    'phone: ' . $tc['phone']
                );
        }
    }
}
