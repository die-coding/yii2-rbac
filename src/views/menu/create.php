<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model diecoding\rbac\models\Menu
 */

$this->title = Yii::t('diecoding-rbac', 'Create Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('diecoding-rbac', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
