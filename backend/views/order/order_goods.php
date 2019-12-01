<?php

use yii\grid\GridView;
use yii\helpers\Html;


$this->title = 'Users';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
?>
<h1>Заказ #<?=$order->id?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',

            [
                'label' => 'Название товара',
                'attribute' => 'title',
            ],
            [
                'label' => 'Цена',
                'attribute' => 'price',
            ],
            [
                'label' => 'Количество',
                'attribute' => 'count',
            ],

            [
                'label' => 'Стоимость',
                'content' => function($data){
                    return $data['count'] * $data['price'];
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],                
        ],
    ]); ?>

    
    
    
    
