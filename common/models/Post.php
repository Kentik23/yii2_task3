<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property int $category_id
 * @property int $status
 * @property string|null $image
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category $category
 */
class Post extends \yii\db\ActiveRecord
{/**
 * @var UploadedFile
 */
    public $imageFile;
    const SCENARIO_UPLOAD_FILE = 'upload_file';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['title', 'text', ...],
            self::SCENARIO_UPLOAD_FILE => ['...', 'imageFile']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'text', 'category_id', 'status'], 'required'],
            [['category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'text', 'image'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'title' => 'Название',
            'text' => 'Содержание',
            'category_id' => 'Категория',
            'status' => 'Статус',
            'image' => 'Фотография',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function beforeValidate()
    {
        if ($this->scenario === self::SCENARIO_UPLOAD_FILE) {
            $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ($this->scenario === self::SCENARIO_UPLOAD_FILE) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $this->setAttribute('image', $this->imageFile->name);
        }
        return parent::beforeSave($insert);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
