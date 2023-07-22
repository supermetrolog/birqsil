<?php

namespace backend\controllers;

use common\actions\ErrorAction;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use yii\db\Connection;
use yii\web\Response;

class SiteController extends AppController
{
    protected array $exceptAuthActions = ['*'];

    public function __construct($id, $module, Connection $db, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $this->response->format = Response::FORMAT_RAW;
        $this->response->headers->set('Content-type', 'image/png');

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data('https://clients.supermetrolog.ru')
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(1000)
            ->margin(30)
            ->build();

        return $result->getString();
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