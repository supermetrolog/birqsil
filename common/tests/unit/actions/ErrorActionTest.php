<?php

declare(strict_types=1);

namespace common\tests\unit\actions;

use backend\controllers\SiteController;
use Codeception\Test\Unit;
use common\actions\ErrorAction;
use Yii;
use yii\base\ErrorHandler as BaseErrorHandler;
use yii\web\ErrorHandler;
use yii\web\NotFoundHttpException;

class ErrorActionTest extends Unit
{
    private ErrorAction $errorAction;

    public function _before(): void
    {
        $this->configureApp();
        $this->errorAction = $this->getErrorAction();
    }

    public function configureApp(): void
    {
        try {
            throw new NotFoundHttpException("Not found");
        } catch (\Throwable $th) {
            $errorHandler = $this->createMock(BaseErrorHandler::class);
            $errorHandler->exception = $th;
            Yii::$app->set('errorHandler', $errorHandler);
        }
    }

    public function getErrorAction(): ErrorAction
    {
        $controller = Yii::createObject(SiteController::class);
        return new ErrorAction('error', $controller);
    }
    public function testRun()
    {
        $result = $this->errorAction->run();

        $exception = Yii::$app->getErrorHandler()->exception;

        verify($result)->equals([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'status' => Yii::$app->getResponse()->getStatusCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'type' => get_class($exception),
            'stack-trace-string' => $exception->getTraceAsString(),
            'stack-trace' => $exception->getTrace(),
        ]);
    }
}
