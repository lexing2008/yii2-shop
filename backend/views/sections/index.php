<?php

use yii\grid\GridView;
use yii\helpers\Html;


$this->title = 'Users';
$this->params['breadcrumbs'][] = ['label' => 'Разделы', 'url' => ['index']];
?>
<h1>Категории товаров</h1>
<p>
<?= Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-primary']); ?>
</p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        
        [
            'label' => 'Название раздела',
            'attribute' => 'title',
        ],
                
        [
            'label' => 'Название в URL',
            'attribute' => 'tid',
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