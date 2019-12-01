<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */

$this->title = $category->page_title;

// регистрируем метатеги
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $category->seo_description
]);

Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $category->seo_keywords
]);

?>
<div class="site-index">
    <h1><?=$category->title?></h1>
    
    <?php
    $size = count($goods);
    for($i=0; $i<$size; ++$i):
        $g = $goods[$i]; ?>
    <p class="goods">
        <div>
            <?php
            if(!empty($g['imgfile_name'])):?>
            <?= Html::a( Html::img('/uploads/goods/250/' . $g['imgfile_name'], ['alt' => $g['title'] ]) , ['shop/goods', 'id' => $g['id'] ]) ?>
            <?php
            endif;?>
        </div>
        <div>
            
            <?= Html::a( $g['title'] , ['shop/goods', 'id' => $g['id'] ]) ?>
            
            <br>
            <span class="price"><?=$g['price']?> руб.</span>
        </div>
    </p>
    <?php
    endfor;?>
    
    <?=LinkPager::widget([
        'pagination' => $pages,
    ]);?>
</div>
