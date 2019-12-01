<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = 'Редактирование раздела: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Разделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
