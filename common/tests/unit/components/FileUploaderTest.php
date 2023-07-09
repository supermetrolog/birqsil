<?php

namespace common\tests\unit\components;

use Codeception\Test\Unit;
use common\components\FileUploader;
use PHPUnit\Framework\MockObject\MockObject;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\web\UploadedFile;

class FileUploaderTest extends Unit
{
    private UploadedFile|MockObject $file;

    protected function _before()
    {
        $this->file = $this->createMock(UploadedFile::class);
    }

    public function getComponent(): FileUploader
    {
        return Yii::$container->get(FileUploader::class);
    }

    public function testUpload(): void
    {
        $this->file->type = 'image/jpeg';
        $this->file->size = 123442;
        $this->file->method('getBaseName')->willReturn('JPEG-FILE.jpeg');
        $this->file->method('getExtension')->willReturn('.jpeg');
        $this->file->method('saveAs')->willReturn(true);

        $component = $this->getComponent();
        $model = $component->upload($this->file);

        verify($model->size)->equals($this->file->size);
        verify($model->type)->equals($this->file->type);
    }

    public function testUploadError(): void
    {
        $this->file->type = 'image/jpeg';
        $this->file->size = 123442;
        $this->file->method('getBaseName')->willReturn('JPEG-FILE.jpeg');
        $this->file->method('getExtension')->willReturn('.jpeg');
        $this->file->method('saveAs')->willReturn(false);

        $this->expectException(ErrorException::class);
        $component = $this->getComponent();
        $model = $component->upload($this->file);
    }
}