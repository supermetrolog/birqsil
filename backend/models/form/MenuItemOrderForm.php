<?php

declare(strict_types=1);

namespace backend\models\form;

use common\base\model\Form;
use common\models\AQ\MenuItemQuery;
use common\models\AR\MenuItem;
use common\models\AR\User;
use common\repositories\MenuItemRepository;
use Yii;
use yii\behaviors\AttributeTypecastBehavior;

class MenuItemOrderForm extends Form
{
    public string|int|null $current_id = null;
    public string|int|null $after_id = null;

    /**
     * @param User $user
     * @param MenuItemRepository $menuItemRepository
     * @param array $config
     */
    public function __construct(
        private readonly User $user,
        private readonly MenuItemRepository $menuItemRepository,
        array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['current_id'], 'required'],
            [['current_id', 'after_id'], 'integer'],
            [
                'current_id',
                'exist',
                'targetClass' => MenuItem::class,
                'targetAttribute' => ['current_id' => 'id'],
                'filter' => fn(MenuItemQuery $query) => $query->byId((int)$this->current_id)->byUserId($this->user->id)->notDeleted()
            ],
            [
                'after_id',
                'exist',
                'skipOnEmpty' => true,
                'targetClass' => MenuItem::class,
                'targetAttribute' => ['after_id' => 'id'],
                'filter' => fn(MenuItemQuery $query) => $query->byId((int)$this->after_id)->byUserId($this->user->id)->notDeleted()
            ],
            [
                ['current_id', 'after_id'],
                'compareRestaurantId'
            ]
        ];
    }

    /**
     * @param string $attr
     * @return void
     */
    public function compareRestaurantId(string $attr): void
    {
        if ($this->hasErrors() || !$this->after_id) {
            return;
        }

        $currentModel = $this->menuItemRepository->findNotDeletedByIdAndUserId((int)$this->current_id, $this->user->id);
        $afterModel = $this->menuItemRepository->findNotDeletedByIdAndUserId((int)$this->after_id, $this->user->id);

        if ($afterModel->restaurant_id !== $currentModel->restaurant_id) {
            $this->addError($attr, Yii::t('backend', 'Restaurant id for both menu items must be the same'));
        }
    }
}