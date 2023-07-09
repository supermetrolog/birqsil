<?php

namespace backend\controllers;

use common\base\exception\ValidateException;
use common\models\AR\MenuItem;
use common\models\form\MenuItemForm;
use common\services\MenuItemService;
use Throwable;
use yii\db\Exception;

class MenuController extends AppController
{
    private MenuItemService $service;

    /**
     * @param $id
     * @param $module
     * @param MenuItemService $service
     * @param array $config
     */
    public function __construct($id, $module, MenuItemService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
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
}