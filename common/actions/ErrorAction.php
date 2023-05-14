<?php

declare(strict_types=1);

namespace common\actions;

use backend\components\response\ErrorResponse;
use Yii;
use yii\base\Action;
use yii\web\Controller;
use yii\web\Response;

class ErrorAction extends Action
{
    private ErrorResponse $errorResponse;
    private Response $response;

    /**
     * @param string $id
     * @param Controller $controller
     */
    public function __construct(string $id, Controller $controller)
    {
        parent::__construct($id, $controller);
        $this->response = Yii::$app->getResponse();
        $this->errorResponse = new ErrorResponse($this->response);
    }



    /**
     * @return array
     */
    public function run(): array
    {
        $this->errorResponse->processed();
        return $this->response->data;
    }
}
