<?php

namespace common\models\AR;

use common\base\model\AR;
use common\enums\AppParams;
use Exception;
use Yii;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $source_name
 * @property string $real_name
 * @property string $full_path
 * @property string $type
 * @property string|null $extension
 * @property int $size
 * @property string $created_at
 * @property string|null $deleted_at
 *
 * @property MenuItem $menuItem
 */
class File extends AR
{
    private string $_link;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['source_name', 'real_name', 'full_path', 'type', 'size'], 'required'],
            [['size'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['source_name', 'real_name', 'full_path', 'type', 'extension'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'source_name' => 'Source Name',
            'real_name' => 'Real Name',
            'full_path' => 'Full Path',
            'type' => 'Type',
            'extension' => 'Extension',
            'size' => 'Size',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getLink(): string
    {
        return Yii::$app->param->get(AppParams::UPLOADED_FILES_URL) . $this->real_name;
    }

    /**
     * @return string[]
     */
    protected function exceptFields(): array
    {
        return ['full_path'];
    }

    /**
     * @return array<string,Closure>
     */
    protected function addFields(): array
    {
        return [
            'link' => fn () => $this->getLink()
        ];
    }

    /**
     * Gets query for [[MenuItem]].
     *
     * @return ActiveQuery
     */
    public function getMenuItem(): ActiveQuery
    {
        return $this->hasOne(MenuItem::class, ['file_id' => 'id']);
    }
}
