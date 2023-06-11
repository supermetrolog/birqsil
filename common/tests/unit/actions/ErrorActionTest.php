<?php

declare(strict_types=1);

namespace common\tests\unit\actions;

use backend\controllers\SiteController;
use common\helpers\HttpCode;
use Yii;
use yii\web\Application;
use Codeception\Test\Unit;
use common\actions\ErrorAction;
use yii\web\NotFoundHttpException;
use LogicException;
use yii\base\ErrorHandler as BaseErrorHandler;
use yii\web\Response;

class ErrorActionTest extends Unit
{
    private ErrorAction $errorAction;
    private Application $app;
    public function _before(): void
    {
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
        $this->app->response->format = Response::FORMAT_JSON;
        $this->app->response->setStatusCode(400);

        $result = $this->errorAction->run();

        $exception = $this->app->getErrorHandler()->exception;

        verify($exception)->notNull();
        verify($result)->equals([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'status' => $this->app->getResponse()->getStatusCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'type' => get_class($exception),
            'stack-trace-string' => $exception->getTraceAsString(),
//            'stack-trace' => $exception->getTrace(),
        ]);
    }
    public function testRunWithNullErrorHandlerException(): void
    {
        $this->app->response->format = Response::FORMAT_JSON;
        $this->app->response->setStatusCode(400);

        $this->app->errorHandler->exception = null;
        $res = $this->errorAction->run();

        verify($res)->equals([
            'message' => 'Server error, exception not found',
            'code' => 1,
            'status' => HttpCode::INTERNAL_SERVER_ERROR->value
        ]);
    }
}
