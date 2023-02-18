<?php

declare(strict_types=1);

namespace common\tests\unit\actions;

use Yii;
use yii\web\Application;
use Codeception\Test\Unit;
use common\actions\ErrorAction;
use yii\web\NotFoundHttpException;
use backend\controllers\SiteController;
use LogicException;
use yii\base\ErrorHandler as BaseErrorHandler;

class ErrorActionTest extends Unit
{
    private ErrorAction $errorAction;
    private Application $app;
    public function _before(): void
    {
        /** @var Application */
        $app = Yii::$app;
        $this->app = $app;
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
            $this->app->set('errorHandler', $errorHandler);
        }
    }

    public function getErrorAction(): ErrorAction
    {
        $controller = Yii::createObject(SiteController::class);
        return new ErrorAction('error', $controller);
    }
    public function testRun(): void
    {
        $result = $this->errorAction->run();

        $exception = $this->app->getErrorHandler()->exception;
        verify($result)->equals([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'status' => $this->app->getResponse()->getStatusCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'type' => get_class($exception),
            'stack-trace-string' => $exception->getTraceAsString(),
            'stack-trace' => $exception->getTrace(),
        ]);
    }
    public function testRunWithNullErrorHandlerException()
    {
        $this->app->errorHandler->exception = null;
        $this->expectException(LogicException::class);
        $this->errorAction->run();
    }
}
