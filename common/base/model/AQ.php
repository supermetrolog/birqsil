<?php

namespace common\base\model;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class AQ extends ActiveQuery
{
    protected $classGetter;

    /**
     * @param callable $classGetter
     * @param string $baseModelClass
     * @param array $config
     */
    public function __construct(callable $classGetter, string $baseModelClass, array $config = [])
    {
        $this->classGetter = $classGetter;
        parent::__construct($baseModelClass, $config);
    }


    /**
     * Converts found rows into model instances.
     * @param array $rows
     * @return array|ActiveRecord[]
     * @since 2.0.11
     */
    protected function createModels($rows): array
    {
        if ($this->asArray) {
            return $rows;
        } else {
            $models = [];
            foreach ($rows as $row) {
                $suck = $this->classGetter;
                /* @var $class ActiveRecord */
                $class = $suck($row);
                $model = $class::instantiate($row);
                $modelClass = get_class($model);
                $modelClass::populateRecord($model, $row);
                $models[] = $model;
            }
            return $models;
        }
    }
}