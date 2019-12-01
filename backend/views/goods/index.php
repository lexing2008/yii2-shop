<?php

use yii\grid\GridView;
use yii\helpers\Html;


$this->title = 'Users';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
?>
<h1>Товары</h1>
<p>
<?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-primary']); ?>
</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'label' => 'Фото',
                'class' => 'yii\grid\DataColumn',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->imgfile_name ? '<img src="/uploads/goods/100/' . $data->imgfile_name . '">' :''; 
                }
            ],
            [
                'label' => 'Название товара',
                'attribute' => 'title',
            ],
            [
                'label' => 'Цена',
                'attribute' => 'price',
            ],
            [
                'label' => 'Скидка, %',
                'attribute' => 'discount',
            ],

            [
                'label' => 'Хит',
                'attribute' => 'is_hit',
                'content' => function($data){
                    return $data['is_hit']? '+' : '' ;
                }
            ],

            [
                'label' => 'Новинка',
                'attribute' => 'is_new',
                'content' => function($data){
                    return $data['is_new']? '+' : '' ;
                }
            ],

            [
                'label' => 'Активен',
                'attribute' => 'active',
                'content' => function($data){
                    return $data['active']? '+' : '' ;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],                

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>

    
    
    
    
