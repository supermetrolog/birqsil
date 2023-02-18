<?php

declare(strict_types=1);

namespace common\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;

class ErrorAction extends Action
{

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $exception = Yii::$app->getErrorHandler()->exception;

        $response = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'status' => Yii::$app->getResponse()->getStatusCode(),
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
