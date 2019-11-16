<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/**
 * @var $this yii\web\View
 * @var $model diecoding\rbac\models\Menu
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('diecoding-rbac', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php ActiveForm::begin(); ?>
        <?= Html::a(Yii::t('diecoding-rbac', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('diecoding-rbac', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?php ActiveForm::end(); ?>
    </p>

    <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'menuParent.name:text:Parent',
                'name',
                'route',
                'icon',
                'order',
                [
                    'attribute' => 'visible',
                    'value' => function ($model) {
                        switch ($model->visible) {
                            case $model::VISIBLE_SHOW:
                                return Yii::t('diecoding-rbac', 'Show');

                            default:
                                return Yii::t('diecoding-rbac', 'Hide');
                        }
                    }
                ],
                [
                    'attribute' => 'options',
                    'format' => 'raw',
                    'value' => function ($model) {

                        return $model::evalOptions($model->options, true);
                    }
                ],
                'data',
            ],
        ])
    ?>

</div>
