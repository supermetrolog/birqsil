<?php

namespace common\fixtures;

use common\models\AR\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}
