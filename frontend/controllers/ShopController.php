<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\Category;
use common\models\Goods;
use common\models\Order;
use common\models\Cart;
use yii\data\Pagination;
use common\models\CategoryList;
use yii\data\ActiveDataProvider;


/**
 * Site controller
 */
class ShopController extends Controller
{
    /**
     * Просмотр страницы Категории
     *
     * @return mixed
     */
    public function actionCategory($tid)
    {
        $category = Category::find()->where(['tid' => $tid, 'active' => 1, 'status' => 1])->one();
        
        // получение товаров категории и подкатегорий
        if( $category->id ){
            $goods_query = Goods::find()->where(['category_id' => $this->get_category_children_ids(), 'active' => 1, 'status' => 1]);
            // постраничная разбивка
            $count_query = clone $goods_query;
            $pages = new Pagination(['totalCount' => $count_query->count(), 
                                        'pageSize' => 20]);
            $goods = $goods_query->offset($pages->offset)
                ->limit($pages->limit)
                ->asArray()
                ->all();
        }
        
        return $this->render('category', ['category' => $category,
                                          'goods' => $goods,
                                          'pages' => $pages]);
    }
    
    
    /**
     * Просмотр товара
     * @param int $id ID товара
     */
    public function actionGoods($id){
        $model = Goods::find()->where(['id' => $id, 'active' => 1, 'status' => 1])->one();
        return $this->render('goods', ['model' => $model]);        
    }

    /**
     * Корзина
     * 
     */
    public function actionCart(){

        $dataProvider = new ActiveDataProvider([
            'query' => Goods::find()->where(['status' => 1, 'active' => 1, 'id' => Cart::get_goods_ids() ]),
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        
        $model = new Order;
        
        if ( $model->load(Yii::$app->request->post()) && $model->save() ) {
            return $this->redirect(['thanks']);
        } else {
            return $this->render('cart', [
                'dataProvider' => $dataProvider,
                'model' => $model
            ]);
        }
    }
    
    
    /**
     * Страница спасибо за покупку
     * @return type
     */
    public function actionThanks(){
        return $this->render('thanks');
    }
    
    /**
     * Добавление в карзину товара
     */
    public function actionAddtocart($goods_id){
        if(Yii::$app->request->isAjax){
            $goods = Goods::find()->where(['id' => $goods_id, 'active' => 1, 'status' => 1])->one();
            $response = [];
            if( empty($goods)){
                $response['error'] = 'Такого товара не существует';
            } else {
                // добавляем товар в куки
                setcookie('cart['. $goods_id.']', ((int)$_COOKIE['cart'][$goods_id] + 1), time() + 3600 * 24 * 365, '/');
                $_COOKIE['cart'][$goods_id] += 1; 
                $response = Cart::calculate();
            }
            return json_encode($response);
        }
    }
    
    /**
     * Получение ID всех потомков категории и самой категории
     * @param int $id
     * @return array
     */
    public function get_category_children_ids($id){
        // поиск потомков категории
        $category_list = new CategoryList('category');
        $children = $category_list->get_children( $id, true );
        $arr_ids = [$id];
        $size = count($children);
        for($i=0; $i<$size; ++$i){
            $arr_ids[] = $children[$i]['id'];
        }
        // - - - поиск потомков категории
        return $arr_ids;
    }
}