<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this yii\web\View */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var $searchModel diecoding\rbac\models\searchs\Menu */

$this->title = Yii::t('diecoding-rbac', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('diecoding-rbac', 'Create Menu'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                [
                    'attribute' => 'menuParent.name',
                    'filter' => Html::activeTextInput($searchModel, 'parent_name', [
                        'class' => 'form-control', 'id' => null
                    ]),
                    'label' => Yii::t('diecoding-rbac', 'Parent'),
                ],
                'route',
                'icon',
                'order',
                [
                    'attribute' => 'visible',
                    'filter' => [
                        $searchModel::VISIBLE_SHOW => Yii::t('diecoding-rbac', 'Show'),
                        $searchModel::VISIBLE_HIDE => Yii::t('diecoding-rbac', 'Hide'),
                    ],
                    'value' => function ($model) {
                        switch ($model->visible) {
                            case $model::VISIBLE_SHOW:
                                return Yii::t('diecoding-rbac', 'Show');
                            
                            default:
                                return Yii::t('diecoding-rbac', 'Hide');
                        }
                    }
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    ?>
    <?php Pjax::end(); ?>

</div>
