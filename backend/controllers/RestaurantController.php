<?php

namespace backend\controllers;

use backend\DTO\RestaurantDto;
use backend\models\form\RestaurantForm;
use common\base\exception\SaveModelException;
use common\base\exception\ValidateException;
use common\components\Param;
use common\enums\AppParams;
use common\enums\RestaurantStatus;
use common\helpers\HttpCode;
use common\models\AR\Restaurant;
use common\services\RestaurantService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use Throwable;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\User;

class RestaurantController extends AppController
{
    protected array $exceptAuthActions = ['qrcode'];
    private RestaurantService $service;
    private Param $param;

    /**
     * @param string $id
     * @param Module $module
     * @param User $user
     * @param RestaurantService $service
     * @param Param $param
     * @param array $config
     */
    public function __construct(
        string $id,
        Module $module,
        User $user,
        RestaurantService $service,
        Param $param,
        array $config = [])
    {
        $this->service = $service;
        $this->param = $param;
        parent::__construct($id, $module, $user, $config);
    }

    /**
     * @return ActiveDataProvider
     */
    public function actionIndex(): ActiveDataProvider
    {
        $query = Restaurant::find()->byUserID($this->user->id)->active();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * @return Restaurant
     * @throws ValidateException
     * @throws Exception
     */
    public function actionCreate(): Restaurant
    {
        $form = new RestaurantForm();
        $form->setScenario(RestaurantForm::SCENARIO_CREATE);

        $form->load($this->request->post());
        $form->user_id = $this->user->identity->id;
        return $form->create();
    }


    /**
     * @param int $id
     * @return Restaurant
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function actionUpdate(int $id): Restaurant
    {
        $model = $this->findModel($id);

        $form = new RestaurantForm();
        $form->setScenario(RestaurantForm::SCENARIO_UPDATE);

        $form->load($this->request->post());
        $form->id = $id;
        $form->update($model);
        return $model;
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): void
    {
        $this->service->delete($this->findModel($id));
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return Restaurant
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): Restaurant
    {
        return $this->findModel($id)->setQrCodeLink(sprintf('%s/v1/restaurant/%d/qrcode', Url::base(true), $id));
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws SaveModelException
     */
    public function actionPublish(int $id): void
    {
        $model = $this->findModel($id);
        $model->setStatus(RestaurantStatus::PUBLISHED);
        $model->saveOrThrow();

        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws SaveModelException
     */
    public function actionHide(int $id): void
    {
        $model = $this->findModel($id);
        $model->setStatus(RestaurantStatus::HIDDEN);
        $model->saveOrThrow();

        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionQrcode(int $id): string
    {
        $this->response->format = Response::FORMAT_RAW;
        $this->response->headers->set('Content-type', 'image/png');

        $restaurant = $this->findModel($id);

        $url = $this->param->get(AppParams::MENU_FRONT_DOMAIN) . "/{$restaurant->unique_name}";

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(1000)
            ->margin(30)
            ->build();

        return $result->getString();
    }

    /**
     * @param int $id
     * @return Restaurant
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Restaurant
    {
        $query = Restaurant::find()->byID($id)->notDeleted();

        if (!$this->user->isGuest) {
            $query->byUserID($this->user->id);
        }

        if ($model = $query->one()) {
            return $model;
        }

        throw new NotFoundHttpException('Restaurant not found');
    }
}