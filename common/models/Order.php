<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Cart;


/**
 * Order model
 *
 */
class Order extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['fio', 'address', 'phones'], 'required' ],
                [['comment'], 'trim'],
                ];
    }
    
    public function beforeSave($insert) {
        $cart = Cart::calculate();
        $this->count = $cart['cart_count'];
        $this->price = $cart['cart_price'];
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes) {
        // добавляем товары в заказ
        if(!empty($_COOKIE['cart'])){
            foreach ($_COOKIE['cart'] as $goods_id => $count) {
                $order_goods = new OrderGoods;
                $order_goods->order_id = $this->id;
                $order_goods->goods_id = $goods_id;
                $order_goods->count = $count;
                
                $goods = Goods::find()->where(['id' => $goods_id, 'active' => 1, 'status' => 1])->one();
                if( $goods->id ){
                    $order_goods->price = $goods->price;
                    $order_goods->title = $goods->title;
                    $order_goods->save(false);
                }
                setcookie('cart['. $goods_id.']', '', time() - 3600 * 24 * 365, '/');
            }
            unset($_COOKIE['cart_price']);
            unset($_COOKIE['cart_count']);
            setcookie('cart_price', '0', time() + 3600 * 24 * 365, '/');
            setcookie('cart_count', '0', time() + 3600 * 24 * 365, '/');
        }
        return parent::afterSave($insert, $changedAttributes);
    }
}