<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use mdm\admin\AutocompleteAsset;

/** 
 * @var $this yii\web\View
 * @var $model diecoding\rbac\models\Menu
 * @var $form yii\widgets\ActiveForm
 */

AutocompleteAsset::register($this);
$opts = Json::htmlEncode([
    'menus' => $model::getMenuSource(),
    'routes' => $model::getSavedRoutes(),
]);
$this->registerJs("var _opts = $opts;");
$this->registerJs($this->render('_script.min.js'));
?>

<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>

            <?= $form->field($model, 'parent_name')->textInput(['id' => 'parent_name']) ?>

            <?= $form->field($model, 'route')->textInput(['id' => 'route']) ?>

            <?= $form->field($model, 'icon')->textInput() ?>

            <?= $form->field($model, 'order')->input('number') ?>
        </div>
        <div class="col-sm-6">

            <?= $form->field($model, 'visible')->dropDownList([
                $model::VISIBLE_SHOW => Yii::t('diecoding-rbac', 'Show'),
                $model::VISIBLE_HIDE => Yii::t('diecoding-rbac', 'Hide'),
            ]) ?>

            <?= $form->field($model, 'options')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="form-group">
        <?=
            Html::submitButton($model->isNewRecord ? Yii::t('diecoding-rbac', 'Create') : Yii::t('diecoding-rbac', 'Update'), ['class' => $model->isNewRecord
                ? 'btn btn-success' : 'btn btn-primary'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>