<?php

namespace backend\controllers;

use backend\models\form\MenuItemOrderForm;
use common\base\exception\SaveModelException;
use common\base\exception\ValidateException;
use common\factories\OrderingServiceFactory;
use common\helpers\HttpCode;
use common\models\AR\MenuItem;
use common\models\form\MenuItemForm;
use common\models\form\MenuItemImageUploadForm;
use common\repositories\MenuItemRepository;
use common\services\MenuItemOrderingService;
use common\services\MenuItemService;
use Throwable;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\User;

class MenuController extends AppController
{
    /**
     * @param string $id
     * @param Module $module
     * @param User $user
     * @param MenuItemService $service
     * @param OrderingServiceFactory $orderingServiceFactory
     * @param MenuItemRepository $menuItemRepository
     * @param array $config
     */
    public function __construct(
        string $id,
        Module $module,
        User $user,
        private readonly MenuItemService $service,
        private readonly OrderingServiceFactory $orderingServiceFactory,
        private readonly MenuItemRepository $menuItemRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $user, $config);
    }

    /**
     * @param int $restaurant_id
     * @return ActiveDataProvider
     */
    public function actionIndex(int $restaurant_id): ActiveDataProvider
    {
        $query = MenuItem::find()
            ->byUserId($this->user->getId())
            ->byRestaurantId($restaurant_id)
            ->notDeleted()
            ->with(['image', 'category', 'unit']);

        return new ActiveDataProvider([
           'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);
    }

    /**
     * @return MenuItem
     * @throws Throwable
     * @throws ValidateException
     * @throws Exception
     */
    public function actionCreate(): MenuItem
    {
        $form = new MenuItemForm();
        $form->setScenario(MenuItemForm::SCENARIO_CREATE);
        $form->load($this->request->post());

        return $this->service->create($form);
    }

    /**
     * @param int $id
     * @return MenuItem
     * @throws NotFoundHttpException
     * @throws ValidateException
     * @throws SaveModelException
     */
    public function actionUpdate(int $id): MenuItem
    {
        $form = new MenuItemForm();
        $form->setScenario(MenuItemForm::SCENARIO_UPDATE);
        $form->load($this->request->post());

        $model = $this->findModel($id);
        $this->service->update($form, $model);

        return $model;
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundHttpException
     * @throws SaveModelException
     */
    public function actionDelete(int $id): void
    {
        $this->service->delete($this->findModel($id));
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws ValidateException
     */
    public function actionUploadFile(int $id): void
    {
        $form = new MenuItemImageUploadForm();
        $form->image = UploadedFile::getInstance($form, 'image');

        $this->service->uploadImage($form, $this->findModel($id));
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @return MenuItem
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): MenuItem
    {
        return $this->findModel($id, ['image']);
    }

    /**
     * @return void
     * @throws NotFoundHttpException
     * @throws Throwable
     */
    public function actionOrder(): void
    {
        $form = new MenuItemOrderForm($this->user->identity, $this->menuItemRepository);
        $form->load($this->request->post());
        $form->ifNotValidThrow();

        $currentModel = $this->findModel($form->current_id);

        $afterOrdering = null;

        if ($form->after_id) {
            $afterModel = $this->findModel($form->after_id);
            $afterOrdering = $afterModel->ordering;
        }

        $menuItemOrdering = new MenuItemOrderingService($this->menuItemRepository, $currentModel);
        $service = $this->orderingServiceFactory->create($menuItemOrdering);

        $service->order($afterOrdering);

        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @param int $id
     * @param array $with
     * @return MenuItem
     * @throws NotFoundHttpException
     */
    private function findModel(int $id, array $with = []): MenuItem
    {
        if ($model = $this->menuItemRepository->findNotDeletedByIdAndUserId($id, $this->user->getId(), $with)) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}