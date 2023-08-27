<?php

declare(strict_types=1);

namespace backend\models\form;

use common\base\model\Form;
use common\models\AQ\CategoryQuery;
use common\models\AR\Category;
use common\models\AR\User;
use common\repositories\CategoryRepository;
use Yii;

class CategoryOrderForm extends Form
{
    public string|int|null $current_id = null;
    public string|int|null $after_id = null;

    /**
     * @param User $user
     * @param CategoryRepository $repository
     * @param array $config
     */
    public function __construct(
        private readonly User $user,
        private readonly CategoryRepository $repository,
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
                'targetClass' => Category::class,
                'targetAttribute' => ['current_id' => 'id'],
                'filter' => fn(CategoryQuery $query) => $query->byId((int)$this->current_id)->byUserId($this->user->id)
            ],
            [
                'after_id',
                'exist',
                'skipOnEmpty' => true,
                'targetClass' => Category::class,
                'targetAttribute' => ['after_id' => 'id'],
                'filter' => fn(CategoryQuery $query) => $query->byId((int)$this->after_id)->byUserId($this->user->id)
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

        $currentModel = $this->repository->findByIdAndUserId((int)$this->current_id, $this->user->id);
        $afterModel = $this->repository->findByIdAndUserId((int)$this->after_id, $this->user->id);

        if ($afterModel->restaurant_id !== $currentModel->restaurant_id) {
            $this->addError($attr, Yii::t('backend', 'Restaurant id for both categories must be the same'));
        }
    }
}