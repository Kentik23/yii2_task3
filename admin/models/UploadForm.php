<?php

namespace admin\models;

use common\models\Post;
use yii\base\Model;
use yii\web\UploadedFile;

/** @var /common/models/Post $model */

class UploadForm extends Post
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $this->setAttribute('image', $this->imageFile->name);
            return true;
        } else {
            $this->setAttribute('image', 'ужас');
            return false;
        }
    }
}