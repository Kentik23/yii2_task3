<?php

namespace api\modules\v1\controllers;

use common\models\Post;
use Yii;

class PostController extends AppController
{
    public function actionView(): array
    {
        $id = $this->getParameterFromRequest('id');

        if ($id) {
            return Post::find()->where(['id' => $id])->one()->toArray();
        }

        $first_item = $this->getParameterFromRequest('first_item', -1);
        $item_count = $this->getParameterFromRequest('item_count', -1);

        $user_id = $this->getParameterFromRequest('user_id');
        $category_id = $this->getParameterFromRequest('category_id');

        if ($user_id && $category_id) {
            return Post::find()->where(['user_id' => $user_id, 'category_id' => $category_id])->limit($item_count)->offset($first_item)->all();
        }

        if ($user_id) {
            return Post::find()->where(['user_id' => $user_id])->limit($item_count)->offset($first_item)->all();
        }

        if ($category_id) {
            return Post::find()->where(['category_id' => $category_id])->limit($item_count)->offset($first_item)->all();
        }

        $array = Post::find()->all();
        return $array;
    }


}