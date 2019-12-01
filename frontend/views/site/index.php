<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Интернет-магазин товаров для дома';
?>
<div class="site-index">
    <div class="goods">
        <div>
            <?= Html::a( Html::img('@web/images/logo.png', ['alt' => 'My logo']) , ['user/view', 'id' => $id]) ?>
        </div>
        <div>
            
            <?= Html::a('Страница товара', ['user/view', 'id' => $id]) ?>
            
            <br>
            <span class="price">15 руб.</span>
        </div>
    </div>
</div>
