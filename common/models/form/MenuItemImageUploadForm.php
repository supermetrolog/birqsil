<?php

namespace common\models\form;

use common\base\model\Form;
use yii\web\UploadedFile;

class MenuItemImageUploadForm extends Form
{
    public UploadedFile|null $image = null;

    public function rules(): array
    {
        return [
            [['image'], 'image', 'extensions' => 'png, jpg, jpeg'],
        ];
    }
}