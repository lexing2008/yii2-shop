<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Корзина';

?>
<div class="site-index">
    <h1>Корзина</h1>
    
<?php
if(empty($_COOKIE['cart'])):?>
    Корзина пуста. Добавьте товар.
<?php
else:?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'label' => 'Количество',
                'content' => function($data){
                    return (int)$_COOKIE['cart'][ $data['id'] ];
                }
            ],

            [
                'label' => 'Стоимость',
                'content' => function($data){
                    return (int)$_COOKIE['cart'][ $data['id'] ] * $data['price'] ;
                }
            ],
        ],
    ]); ?>
    
    
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'] ]); ?>

    <?= $form->field($model, 'fio')->textInput()->label('ФИО') ?>
    <?= $form->field($model, 'address')->textInput()->label('Адрес') ?>
    <?= $form->field($model, 'phones')->textInput()->label('Телефоны') ?>
    <?= $form->field($model, 'comment')->textarea(['rows' => 6, 'cols' => 5])->label('Комментарий к заказу') ?>

    <div class="form-group">
        <?= Html::submitButton('Заказать', ['class' => 'btn btn-success'] ) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
<?php
endif;?>    
</div>
