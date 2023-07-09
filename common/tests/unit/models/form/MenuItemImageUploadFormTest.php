<?php

namespace common\tests\unit\models\form;

use Codeception\Test\Unit;
use common\models\form\MenuItemImageUploadForm;
use yii\web\UploadedFile;

class MenuItemImageUploadFormTest extends Unit
{

    public function testValidate(): void
    {
        $testCases = [
            [
                'desc' => 'Valid data',
                'data' => [
                    'name' => 'JPEG-FILE.jpeg',
                    'fullPath' => 'JPEG-FILE.jpeg',
                    'type' => 'image/jpeg',
                    'tempName' => codecept_data_dir('JPEG-FILE.jpeg'),
                    'error' => 0,
                    'size' => 184949
                ],
                'isValid' => true,
            ],
            [
                'desc' => 'Valid data',
                'data' => [
                    'name' => 'PNG-FILE.jpeg',
                    'fullPath' => 'PNG-FILE.png',
                    'type' => 'image/png',
                    'tempName' => codecept_data_dir('PNG-FILE.png'),
                    'error' => 0,
                    'size' => 184949
                ],
                'isValid' => true,
            ],
            [
                'desc' => 'Valid data',
                'data' => [
                    'name' => 'JPEG-FILE.gif',
                    'fullPath' => 'JPEG-FILE.gif',
                    'type' => 'image/gif',
                    'tempName' => codecept_data_dir('JPEG-FILE.jpeg'),
                    'error' => 0,
                    'size' => 184949
                ],
                'isValid' => false,
            ],
        ];

        foreach ($testCases as $tc) {
            $form = new MenuItemImageUploadForm();
            $form->image = new UploadedFile($tc['data']);

            verify($form->validate())->equals($tc['isValid'], $tc['desc'] . json_encode($form->getErrors()));
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }
}