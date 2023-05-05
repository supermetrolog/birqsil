<?php

use common\models\AR\User;
use yii\web\View;

/** @var View $this */
/** @var User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
Hello <?= $user->email ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>