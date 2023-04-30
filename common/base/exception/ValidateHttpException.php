<?php

namespace common\base\exception;

use Yii;
use yii\helpers\Json;
use yii\web\HttpException;

class ValidateHttpException extends HttpException
{
    public $statusCode = 422;
    private string $statusText;

    /**
     * @param ValidateException $th
     */
    public function __construct(ValidateException $th)
    {
        $this->statusText = Yii::t('common', 'Validate error');
        $message = Json::encode($th->getModel()->getErrorSummary(false));

        parent::__construct(422, $message, 1, $th);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->statusText;
    }
}