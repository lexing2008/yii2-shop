<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = $model->page_title;

// регистрируем метатеги
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $model->seo_description
]);

Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $model->seo_keywords
]);

?>
<div class="site-index">
    <h1><?=$model->title?></h1>
    <p>
        <?php
        if(!empty($model->imgfile_name)):?>
            <?= Html::img('/uploads/goods/450/' . $model->imgfile_name, ['alt' => $model->title ]) ?>
        <?php
        endif;?>
    </p>

    <p>
        Цена: <b><?=$model->price?> руб.</b> 
        <div id="add_to_cart" class="btn btn-lg btn-success">ДОБАВИТЬ В КОРЗИНУ</div>
        <input type="hidden" id="goods_id" value="<?=$model->id?>">
    </p>
    
    <p>
        <b>Описание:</b>
        <?=$model->description?>
    </p>
    
    
<?php
$js = 'url = "' . Url::toRoute(['shop/addtocart', 'jsoncallback'=> '?']) . '"; ';
$js .= <<<JS
    $('#add_to_cart').click( function(){
        goods_id = $('#goods_id').val();
        $.getJSON(url, {
                goods_id: goods_id,
        }, function (response, textStatus) {
            if (response.error) {

            } else {
                $('#cart_count').text( response.cart_count);
                $('#cart_price').text( response.cart_price );
            }
        });	

        return false;
    });
JS;
$this->registerJs($js);
?>
    

</div>
