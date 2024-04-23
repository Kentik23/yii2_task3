<?php

namespace common\models;

use admin\modules\rbac\models\ModelSearch;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
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
{
    /**
     * {@inheritdoc}
     */
    public $imageFile;
    /**
     * @var int|mixed|string|null
     */

    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'text', 'category_id'], 'required'],
            [['category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'text', 'image'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate())
        {
            if ($this->imageFile != null) {
                $this->imageFile->saveAs('C:\OSPanel\domains\backend-yii2-template-master\htdocs\uploads\img' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
                $this->image = $this->imageFile->name;
            }
            return true;
        } else
            return false;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->upload() && parent::save(false, $attributeNames))
            return true;
        else
            return false;
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
