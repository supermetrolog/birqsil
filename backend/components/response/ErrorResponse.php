<?php

namespace backend\components\response;

use common\base\exception\ValidateHttpException;
use common\helpers\HttpCode;
use LogicException;
use Throwable;
use Yii;
use yii\web\Application;
use yii\web\Response;

class ErrorResponse
{
    private Application $app;
    public function __construct(private readonly Response $response)
    {
        $this->app = Yii::$app;
    }


    /**
     * @return Throwable
     */
    private function getExceptionOrThrow(): Throwable
    {
        $ex = $this->app->getErrorHandler()->exception;
        codecept_debug($ex);
        if ($ex === null) {
            throw new LogicException('Error handler exception cannot be null');
        }
        return $ex;
    }

    public function processed(): void
    {
        if ($this->response->format !== Response::FORMAT_JSON || $this->response->isSuccessful) {
            return;
        }
        $this->app->response->format = Response::FORMAT_JSON;
        $exception = $this->getExceptionOrThrow();

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
            $response['stack-trace'] = $exception->getTrace();
        }

        $this->response->data = $response;
    }
}