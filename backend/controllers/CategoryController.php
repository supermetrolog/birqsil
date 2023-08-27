<?php

namespace backend\controllers;

use backend\models\form\CategoryForm;
use backend\models\form\CategoryOrderForm;
use common\base\exception\ValidateException;
use common\factories\OrderingServiceFactory;
use common\helpers\HttpCode;
use common\models\AR\Category;
use common\repositories\CategoryRepository;
use common\services\CategoryOrderingService;
use Exception;
use Throwable;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class CategoryController extends AppController
{
    /**
     * @param string|null $id
     * @param Module $module
     * @param User $user
     * @param CategoryRepository $repository
     * @param OrderingServiceFactory $orderingServiceFactory
     * @param array $config
     */
    public function __construct(
        ?string $id,
        Module $module,
        User $user,
        private readonly CategoryRepository $repository,
        private readonly OrderingServiceFactory $orderingServiceFactory,
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
        $query = Category::find()
            ->byUserId($this->user->getId())
            ->byRestaurantId($restaurant_id);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'ordering' => SORT_ASC
                ]
            ]
        ]);
    }

    /**
     * @param int $id
     * @return Category
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): Category
    {
        return $this->findModel($id);
    }

    /**
     * @return Category
     * @throws ValidateException
     * @throws Exception
     */
    public function actionCreate(): Category
    {
        $form = new CategoryForm();
        $form->setScenario(CategoryForm::SCENARIO_CREATE);

        $form->load($this->request->post());

        return $form->create();
    }


    /**
     * @param int $id
     * @return Category
     * @throws NotFoundHttpException
     * @throws ValidateException
     */
    public function actionUpdate(int $id): Category
    {
        $model = $this->findModel($id);

        $form = new CategoryForm();
        $form->setScenario(CategoryForm::SCENARIO_UPDATE);

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
        $this->findModel($id)->delete();
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @return void
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws ValidateException
     */
    public function actionOrder(): void
    {
        $form = new CategoryOrderForm($this->user->identity, $this->repository);
        $form->load($this->request->post());
        $form->ifNotValidThrow();

        $currentModel = $this->findModel($form->current_id);

        $afterOrdering = null;

        if ($form->after_id) {
            $afterModel = $this->findModel($form->after_id);
            $afterOrdering = $afterModel->ordering;
        }

        $menuItemOrdering = new CategoryOrderingService($this->repository, $currentModel);
        $service = $this->orderingServiceFactory->create($menuItemOrdering);

        $service->order($afterOrdering);

        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }


    /**
     * @param int $id
     * @return Category
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Category
    {
        $query = Category::find()->byID($id);

        if (!$this->user->isGuest) {
            $query->byUserID($this->user->id);
        }

        if ($model = $query->one()) {
            return $model;
        }

        throw new NotFoundHttpException('Category not found');
    }
}