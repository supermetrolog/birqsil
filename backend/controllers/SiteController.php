<?php

namespace backend\controllers;

use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use common\actions\ErrorAction;
use Yii;
use yii\db\Connection;
use yii\web\Response;
use yii\web\User;

class SiteController extends AppController
{
    protected array $exceptAuthActions = ['*'];

    public function __construct($id, $module, Connection $db, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $this->response->format = Response::FORMAT_HTML;

        $options = new QROptions();

        $qr = new QRCode($options);
        $qr->

        $data = 'https://birqsil.ru';

        return  '<img src="'.$qr->render($data).'" alt="QR Code" />';
    }

    /**
     * @return array[]
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }
}