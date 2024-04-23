<?php

namespace api\modules\v1\controllers;

use common\models\Post;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class PostController extends AppController
{
    public function actionView(): array
    {
        $id = $this->getParameterFromRequest('id');
        $first_item = $this->getParameterFromRequest('first_item', -1);
        $item_count = $this->getParameterFromRequest('item_count', -1);
        $user_id = (int)Yii::$app->user->id;
        $category_id = $this->getParameterFromRequest('category_id');

        if ($id) return $this->findModel(['id' => $id])->toArray();

        if ($user_id && $category_id) return Post::find()->where(['user_id' => $user_id, 'category_id' => $category_id])->limit($item_count)->offset($first_item)->all();

        if ($user_id) return Post::find()->where(['user_id' => $user_id])->limit($item_count)->offset($first_item)->all();

        $array = Post::find()->all();

        return $array;
    }

    public function actionCreate(): array
    {
        $post = Yii::$app->request->post();

        if (!$post) {
            return $this->returnError(Yii::t('app', 'Data required'));
        }

        $model = new Post();
        $model->load($post, '');
        $model->status = 10;
        $model->user_id = (int)Yii::$app->user->id;
        $model->imageFile = UploadedFile::getInstanceByName('imageFile');

        if ($model->save()) {
            return $model->toArray();
        } else {
            return $this->returnError($model->errors);
        }
    }

    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
        if (!$post) {
            return $this->returnError(Yii::t('app', 'Data required'));
        }

        $model = $this->findModel(['id' => $id]);
        $model->load($post, '');
        $model->imageFile = UploadedFile::getInstanceByName('imageFile');
        if ($model->save()) {
            return $model->toArray();
        } else {
            return $this->returnError($model->errors);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel(['id' => $id]);

        if ($model != null) {
            $model->delete();
            return $this->returnSuccess('message', 'success');
        } else
            return $this->returnError('message',  'post not found');
    }

    protected function findModel($id)
    {
        if (($model = Post::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Post with these parameters not found');
    }
}