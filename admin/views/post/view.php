<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Post $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить пост?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
                'attribute' => 'text',
                'format' => 'html',
            ],
            ['attribute' => 'category_id',
                'value' => function ($model) {
                    return $model->category->title;
                },
            ],
            ['attribute' => 'status',
                'value' => function ($model) {
                    return \common\models\StatusHelper::getStatusAsString($model->status);
                }
            ],
            ['attribute' => 'image',
                'value' => function ($model) {
                    return Html::img('/uploads/img/' . $model->image);
                },
                'format' => 'html'
            ],
            ['attribute' => 'created_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDateTime($model->created_at, 'php: d.m.Y H:i');
                },
            ],
            ['attribute' => 'updated_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDateTime($model->updated_at, 'php: d.m.Y H:i');
                },
            ],
        ],
    ]) ?>

</div>
