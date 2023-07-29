<?php

namespace common\components\response;

use common\base\exception\ValidateHttpException;
use common\helpers\HttpCode;
use Throwable;
use Yii;
use yii\web\Application;
use yii\web\Response;

class ErrorResponse
{
    private Application $app;

    /**
     * @param Response $response
     */
    public function __construct(private readonly Response $response)
    {
        $this->app = Yii::$app;
    }


    /**
     * @return Throwable|null
     */
    private function getException(): Throwable|null
    {
        return $this->app->getErrorHandler()->exception;
    }

    /**
     * @return void
     */
    public function processed(): void
    {
        if ($this->response->isSuccessful) {
            return;
        }

        $this->app->response->format = Response::FORMAT_JSON;
        $exception = $this->getException();

        if (!$exception) {
            $this->response->data = [
                'message' => 'Server error, exception not found',
                'code' => 1,
                'status' => HttpCode::INTERNAL_SERVER_ERROR->value
            ];

            return;
        }

        $response = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'status' => $this->response->getStatusCode()
        ];

        if ($exception instanceof ValidateHttpException) {
            $response['errors'] = $exception->getErrors();
        }

        if (!YII_ENV_PROD) {
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['type'] = get_class($exception);
            $response['stack-trace-string'] = $exception->getTraceAsString();
//            $response['stack-trace'] = $exception->getTrace(); TODO: Переполнение памяти
        }

        $this->response->data = $response;
    }
}