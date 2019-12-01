<?php
use yii\helpers\Html;
?>
<div id="cart">
    <?=Html::a( Html::img('/uploads/cart.png', ['alt' => 'Корзина товаров']), ['shop/cart'])?>
    <span id="cart_count"><?=(double)$_COOKIE['cart_count']?></span> шт. |    
    <span id="cart_price"><?=(double)$_COOKIE['cart_price']?></span> руб.
    
    <span class="phone">МТС: +375 29 777-55-33 | Velcom: +375 44 777-55-33</span>
</div>
