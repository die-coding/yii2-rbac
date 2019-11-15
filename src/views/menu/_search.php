<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model diecoding\rbac\models\searchs\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'parent') ?>

    <?= $form->field($model, 'route') ?>

    <?= $form->field($model, 'assign') ?>

    <?php // echo $form->field($model, 'visible') ?>

    <?php // echo $form->field($model, 'icon') ?>

    <?php // echo $form->field($model, 'order') ?>

    <?php // echo $form->field($model, 'options') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('diecoding-rbac', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('diecoding-rbac', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
