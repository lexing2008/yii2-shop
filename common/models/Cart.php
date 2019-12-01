<?php
namespace common\models;

use Yii;



/**
 * Cart model
 *
 */
class Cart{
    /**
     * Подсчитывает товары в корзине
     * @return array
     */
    public static function calculate(){
        $cart_price = 0;
        $cart_count = 0;
        if( !empty($_COOKIE['cart']) ){
            foreach ($_COOKIE['cart'] as $goods_id => $count) {
                $goods = Goods::find()->where(['id' => $goods_id, 'active' => 1, 'status' => 1])->one();
                $cart_count += $count;
                $cart_price += $count * $goods->price;
            }
        }
        // добавляем информацию в куки
        setcookie('cart_price', $cart_price, time() + 3600 * 24 * 365, '/');
        setcookie('cart_count', $cart_count, time() + 3600 * 24 * 365, '/');
        
        return ['cart_count' => $cart_count, 'cart_price' => $cart_price];
    }
    
    /**
     * Возвращает массив ID товаров в корзине
     * @return array
     */
    public static function get_goods_ids(){
        $arr_ids = [];
        if( !empty($_COOKIE['cart']) ){
            foreach ($_COOKIE['cart'] as $goods_id => $count){
                $arr_ids[] = $goods_id;
            }
        }
        return $arr_ids;
    }
    
}