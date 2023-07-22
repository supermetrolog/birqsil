<?php

namespace backend\DTO;

use common\models\AR\Restaurant;
use yii\base\BaseObject;

class RestaurantDto extends BaseObject
{
    public int $id;
    public int $user_id;
    public string $name;
    public string|null $legal_name;
    public string|null $address;
    public int $status;
    public string $created_at;
    public string|null $updated_at;
    public string|null $deleted_at;
    public string $qrcodeLink;

    /**
     * @param Restaurant $model
     * @param string $qrcodeLink
     */
    public function __construct(Restaurant $model, string $qrcodeLink)
    {
        $this->qrcodeLink = $qrcodeLink;
        parent::__construct($model->getAttributes());
    }
}