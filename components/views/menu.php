
    <?php
    use yii\helpers\Html;
    
    if( !empty($items) ):
        foreach ($items as $key => $item):?>
        <div class="level_<?=$item['level']?><?=$item['id'] == $current_category_id ? ' selected' : '' ?>">
            <?= Html::a($item['title'], ['shop/category', 'tid' => $item['tid']]) ?>
        </div>
    <?
        endforeach;
    endif;
    ?>
