<?php

namespace common\services;

use common\enums\RestaurantStatus;
use common\models\AR\Restaurant;
use Throwable;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\StaleObjectException;

readonly class RestaurantService
{
    /**
     * @param Connection $db
     */
    public function __construct(private Connection $db)
    {
    }

    /**
     * @param Restaurant $model
     * @return void
     * @throws Throwable
     * @throws Exception
     * @throws StaleObjectException
     */
    public function delete(Restaurant $model): void
    {
        $tx = $this->db->beginTransaction();

        try {
            $model->delete();
            $model->setStatus(RestaurantStatus::DELETED);
            $model->saveOrThrow();
            $tx->commit();
        } catch (Throwable $th) {
            $tx->rollBack();
            throw $th;
        }
    }
}