<?php

namespace common\components;

use common\base\exception\ValidateException;
use common\models\AR\File;
use Throwable;
use yii\base\ErrorException;
use yii\db\Connection;
use yii\web\UploadedFile;

readonly class FileUploader
{
    /**
     * @param Connection $db
     * @param string $basePath
     */
    public function __construct(private Connection $db, private string $basePath)
    {
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param UploadedFile $file
     * @return File
     * @throws Throwable
     * @throws ValidateException
     */
    public function upload(UploadedFile $file): File
    {
        $tx = $this->db->beginTransaction();

        try {
            $model = new File();

            $model->source_name = $file->getBaseName();
            $model->extension = $file->getExtension();
            $model->type = $file->type;
            $model->size = $file->size;

            $this->generateRealName($model);
            $this->generateFullPath($model);

            $model->saveOrThrow();

            $this->saveToFileSystem($model, $file);

            $tx->commit();
            return $model;
        } catch (Throwable $th) {
            $tx->rollBack();
            throw $th;
        }
    }

    /**
     * @param File $model
     * @param UploadedFile $file
     * @return void
     * @throws ErrorException
     */
    private function saveToFileSystem(File $model, UploadedFile $file): void
    {
        if (!$file->saveAs($model->full_path)) {
            throw new ErrorException('File save to file system error');
        }
    }

    /**
     * @param File $model
     * @return void
     */
    private function generateRealName(File $model): void
    {
        $model->real_name = md5($model->source_name . time() . $model->extension) . '.' . $model->extension;
    }

    /**
     * @param File $model
     * @return void
     */
    private function generateFullPath(File $model): void
    {
        $model->full_path =  $this->getBasePath() . $model->real_name;
    }

}