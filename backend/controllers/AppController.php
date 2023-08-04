<?php

namespace backend\controllers;

use common\base\exception\ValidateException;
use common\base\exception\ValidateHttpException;
use yii\base\InvalidRouteException;
use yii\base\Module;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\User;

class AppController extends Controller
{
    /**
     * @var string[]
     */
    protected array $exceptAuthActions = [];

    protected User $user;

    /**
     * @param string|null $id
     * @param Module $module
     * @param User $user
     * @param array $config
     */
    public function __construct(string|null $id, Module $module, User $user, array $config = [])
    {
        $this->user = $user;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                [
                    'class' => 'yii\filters\ContentNegotiator',
                    'formats' => [
                        'application/json' => Response::FORMAT_JSON,
                        'application/xml' => Response::FORMAT_JSON,
                    ],
                ],
                [
                    'class' => Cors::class,
                    'cors' => [
                        'Origin' => ['*'],
                        'Access-Control-Request-Method' => ['*'],
                        'Access-Control-Request-Headers' => ['Origin', 'Content-Type', 'Accept', 'Authorization']
                    ]
                ],
                [
                    'class' => HttpBearerAuth::class,
                    'except' => $this->exceptAuthActions,
                ]
            ]
        );
    }

    /**
     * @param string $id
     * @param array $params
     * @return mixed
     * @throws ValidateHttpException
     * @throws InvalidRouteException
     */
    public function runAction($id, $params = []): mixed
    {
        try {
            return parent::runAction($id, $params);
        } catch (ValidateException $th) {
            throw new ValidateHttpException($th);
        }
    }
}