<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Добро пожаловать в панель администратора!</h1>

        <p class="lead">Мы рады видеть Вас в нашей панели администратора.</p>

        <p>
            <?= Html::a('Добавьте товар', ['/goods/create'], ['class' => 'btn btn-lg btn-success']) ?>
            <?= Html::a('Создайте категорию товара', ['/category/create'], ['class' => 'btn btn-lg btn-success']) ?>
            <?= Html::a('Создайте страницу', ['/sections/create'], ['class' => 'btn btn-lg btn-success']) ?>
            <br><br>
            <?= Html::a('Управляйте заказами', ['/order/index'], ['class' => 'btn btn-lg btn-success']) ?>
            
        </p>
    </div>

    </div>
</div>
