<?php

use yii\grid\GridView;
use yii\helpers\Html;


$this->title = 'Users';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
?>
<h1>Пользователи</h1>
<p>
<?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-primary']); ?>
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
                return $data->file_photo ? '<img src="/uploads/user/100/' . $data->file_photo . '">' :''; // $data['name'] для массивов, например, при использовании SqlDataProvider.
            }
        ],
        
        [
            'label' => 'Пользователь',
            'attribute' => 'username'
        ],

        'email',

        [
            'label' => 'Создан',
            'class' => 'yii\grid\DataColumn',
            'value' => function ($data) {
                return date( 'd/m/Y H:i', $data->created_at); // $data['name'] для массивов, например, при использовании SqlDataProvider.
            }
        ],
                
        [
            'label' => 'Обновлен',
            'class' => 'yii\grid\DataColumn',
            'value' => function ($data) {
                return date( 'd/m/Y H:i', $data->updated_at); // $data['name'] для массивов, например, при использовании SqlDataProvider.
            }
        ],

        [
            'label' => 'Активен',
            'attribute' => 'status',
            'content' => function($data){
                return $data['status'] == 10? '+' : '' ;
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