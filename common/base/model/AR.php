<?php

namespace common\base\model;

use common\base\exception\SaveModelException;
use common\base\exception\ValidateException;
use DateTime;
use Throwable;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

class AR extends ActiveRecord
{
    public const SOFT_DELETE_ATTRIBUTE = 'deleted_at';

    protected bool $useSoftDelete = true;

    /**
     * @param bool $validate
     * @return void
     * @throws SaveModelException
     */
    public function saveOrThrow(bool $validate = true): void
    {
        if (!$this->save($validate)) {
            throw new SaveModelException($this);
        }
    }

    /**
     * @return void
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function delete(): void
    {
        if ($this->useSoftDelete) {
            $this->softDelete();
        } else {
            if (!parent::delete()) {
                throw new ErrorException('Delete model error');
            }
        }
    }

    /**
     * @return void
     * @throws ErrorException
     * @throws SaveModelException
     */
    protected function softDelete(): void
    {
        if (!$this->hasAttribute(self::SOFT_DELETE_ATTRIBUTE)) {
            throw new ErrorException('Soft delete attribute (' . self::SOFT_DELETE_ATTRIBUTE . ') not exist');
        }

        $this->setAttribute(self::SOFT_DELETE_ATTRIBUTE, (new DateTime())->format('Y-m-d H:i:s'));
        $this->saveOrThrow();
    }

    /**
     * @return array
     */
    protected function exceptFields(): array
    {
        return [];
    }

    /**
     * @return array<string,Closure>
     */
    protected function addFields(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        $fields = parent::fields();

        foreach ($this->exceptFields() as $exceptField) {
            unset($fields[$exceptField]);
        }

        foreach ($this->addFields() as $key => $addField) {
            $fields[$key] = $addField;
        }

        return $fields;
    }

}