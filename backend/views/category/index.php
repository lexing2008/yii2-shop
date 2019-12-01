<?php

use yii\grid\GridView;
use yii\helpers\Html;


$this->title = 'Users';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
?>
<h1>Категории товаров</h1>
<p>
<?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-primary']); ?>
</p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        
        [
            'label' => 'Название категории',
            'attribute' => 'title',
            'content' => function($data){
                return str_repeat('- - ', $data['level'] ) . $data['title'];
            }
        ],
                
        [
            'label' => 'Название в URL',
            'attribute' => 'tid',
        ],

        [
            'label' => 'Активна',
            'attribute' => 'active',
            'content' => function($data){
                return $data['active']? '+' : '' ;
            }
        ],
                
        [
            'label' => 'Правка',
            'content' => function($data){
                return '<a href="/backend/web/index.php?r=category%2Fupdate&amp;id=' . $data['id'] . '" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>';
            },
        ],
                

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
        ],
    ],
]); ?>