<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Cart;


/**
 * Order model
 *
 */
class OrderGoods extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_goods';
    }
}