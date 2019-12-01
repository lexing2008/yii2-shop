<?php

use yii\grid\GridView;
use yii\helpers\Html;


$this->title = 'Users';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
?>
<h1>Заказы</h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',

            [
                'label' => 'ФИО',
                'attribute' => 'fio',
            ],
            [
                'label' => 'Адрес',
                'attribute' => 'address',
            ],
            [
                'label' => 'Телефоны',
                'attribute' => 'phones',
            ],
            [
                'label' => 'Комментарий',
                'attribute' => 'comment',
            ],
            [
                'label' => 'Стоимость',
                'attribute' => 'price',
            ],
            [
                'label' => 'Количество товаров',
                'attribute' => 'count',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],                
        ],
    ]); ?>

    
    
    
    
