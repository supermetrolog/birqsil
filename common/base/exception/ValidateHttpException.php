<?php

namespace common\base\exception;

use common\helpers\HttpCode;
use yii\helpers\Json;
use yii\web\HttpException;

class ValidateHttpException extends HttpException
{
    public $statusCode;
    private string $statusText;

    /**
     * @param ValidateException $th
     */
    public function __construct(ValidateException $th)
    {
        $this->statusCode = HttpCode::VALIDATE_ERROR->value;
        $this->statusText = HttpCode::VALIDATE_ERROR->toString();
        $message = Json::encode($th->getModel()->getErrorSummary(false));

        parent::__construct($this->statusCode, $message, 1, $th);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->statusText;
    }
}