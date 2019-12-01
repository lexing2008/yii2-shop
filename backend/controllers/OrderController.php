<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Goods;
use common\models\Order;
use common\models\OrderGoods;
use yii\data\ActiveDataProvider;

/**
 * Order controller
 */
class OrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Просмотр списка заказов
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр заказа
     *
     * @return string
     */
    public function actionView($id)
    {
        $order = Order::find()->where(['id' =>$id] )->one();

        $dataProvider = new ActiveDataProvider([
            'query' => OrderGoods::find()->where(['order_id' => $id]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('order_goods', [
            'order' => $order,
            'dataProvider' => $dataProvider,
        ]);
    }

    
}
