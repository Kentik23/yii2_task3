<?php

namespace api\modules\v1\controllers;

use common\models\Post;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class PostController extends AppController
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authentificator' => [
                'class' => HttpBearerAuth::class,
                'except' => ['view']
            ],
        ]);
    }
    public function actionView(): array
    {
        $id = $this->getParameterFromRequest('id');
        $first_item = $this->getParameterFromRequest('first_item', -1);
        $item_count = $this->getParameterFromRequest('item_count', -1);
        $user_id = $this->getParameterFromRequest('user_id');
        $category_id = $this->getParameterFromRequest('category_id');

        if ($id) return $this->findModel(['id' => $id])->toArray();

        if ($user_id && $category_id) return Post::find()->where(['user_id' => $user_id, 'category_id' => $category_id])->limit($item_count)->offset($first_item)->all();

        if ($category_id) return Post::find()->where(['category_id' => $category_id])->limit($item_count)->offset($first_item)->all();

        if ($user_id) return Post::find()->where(['user_id' => $user_id])->limit($item_count)->offset($first_item)->all();

        return Post::find()->all();;
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

        if ($model->user_id == (int)Yii::$app->user->id) {
            $model->load($post, '');
            $model->imageFile = UploadedFile::getInstanceByName('imageFile');
            if ($model->save()) {
                return $model->toArray();
            } else {
                return $this->returnError($model->errors);
            }
        } else
            return $this->returnError('message',  'post access denied');
    }

    public function actionDelete($id)
    {
        $model = $this->findModel(['id' => $id]);

        if ($model != null) {
            if ($model->user_id == (int)Yii::$app->user->id) {
                $model->delete();
                return $this->returnSuccess('message', 'success');
            } else
                return $this->returnError('message',  'post access denied');
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