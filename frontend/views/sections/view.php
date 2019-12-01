<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\Sections;

$this->title = $model->page_title;
$this->params['breadcrumbs'][] = $model->title;

// регистрируем метатеги
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $model->seo_description
]);

Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $model->seo_keywords
]);
?>
<div class="site-about">
    <h1><?= Html::encode($model->title) ?></h1>

    <p><?= $model->text ?></p>

</div>