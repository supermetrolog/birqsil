<?php

namespace backend\controllers;

use app\models\form\SignInForm;
use app\models\form\SignUpForm;
use common\actions\ErrorAction;
use common\base\exception\ValidateException;
use common\base\exception\ValidateHttpException;
use common\components\Notifier;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\rest\Controller;

class SiteController extends Controller
{
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * @return string
     * @throws ValidateHttpException
     * @throws Throwable
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function actionSignup(): string
    {
        $form = new SignUpForm(
            new Notifier(),
            new SignInForm(),
            Yii::$app->db
        );

        try {
            $form->load($this->request->post());
            return $form->signUp();
        } catch (ValidateException $th) {
            throw new ValidateHttpException($th);
        }
    }
}
