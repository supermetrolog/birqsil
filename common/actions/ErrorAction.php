<?php

declare(strict_types=1);

namespace common\actions;

use Yii;
use yii\base\Action;
use yii\web\Application;
use yii\web\Response;

class ErrorAction extends Action
{

    public function run()
    {
        /** @var Application */
        $app = Yii::$app;
        $app->response->format = Response::FORMAT_JSON;

        $exception = $app->getErrorHandler()->exception;

        $response = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'status' => $app->getResponse()->getStatusCode(),
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
