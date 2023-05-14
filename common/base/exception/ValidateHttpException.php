<?php

namespace common\base\exception;

use common\helpers\HttpCode;
use yii\helpers\Json;
use yii\web\HttpException;

class ValidateHttpException extends HttpException
{
    public $statusCode;
    private array $errors;
    private string $statusText;

    /**
     * @param ValidateException $th
     */
    public function __construct(ValidateException $th)
    {
        $this->statusCode = HttpCode::VALIDATE_ERROR->value;
        $this->statusText = HttpCode::VALIDATE_ERROR->toString();
        $this->errors = $th->getModel()->getErrorSummary(true);
        parent::__construct($this->statusCode, 'Validate error', 1, $th);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->statusText;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}