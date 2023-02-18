<?php

declare(strict_types=1);

namespace common\actions;

use LogicException;
use Throwable;
use Yii;
use yii\base\Action;
use yii\web\Application;
use yii\web\Controller;
use yii\web\Response;

class ErrorAction extends Action
{
    private Application $app;

    public function __construct(string $id, Controller $controller)
    {
        parent::__construct($id, $controller);
        /** @var Application */
        $app = Yii::$app;
        $this->app = $app;
    }


    private function setJSONResponseFormat(): void
    {
        $this->app->response->format = Response::FORMAT_JSON;
    }
    private function getExceptionOrThrow(): Throwable
    {
        $ex = $this->app->getErrorHandler()->exception;
        if ($ex === null) {
            throw new LogicException('error handler exception cannot be null');
        }
        return $ex;
    }
    private function getStatusCode(): int
    {
        return $this->app->getResponse()->getStatusCode();
    }
    public function run(): array
    {
        $this->setJSONResponseFormat();
        $exception = $this->getExceptionOrThrow();

        $response = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'status' => $this->getStatusCode(),
        ];
        if (!YII_ENV_PROD) {
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['type'] = get_class($exception);
            $response['stack-trace-string'] = $exception->getTraceAsString();
            $response['stack-trace'] = $exception->getTrace();
        }

        return $response;
    }
}
