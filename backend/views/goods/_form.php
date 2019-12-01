<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CategoryList;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

$catlist = new CategoryList('category');
$arr_data = $catlist->get_data_for_drop_down_list();
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'] ]); ?>

    <?= $form->field($model, 'page_title')->textInput(['maxlength' => true])->label('SEO. Заголовок страницы') ?>
    <?= $form->field($model, 'title')->textInput()->label('Название товара') ?>
    <?= $form->field($model, 'price')->textInput()->label('Цена') ?>
    <?= $form->field($model, 'discount')->textInput()->label('Скидка, %') ?>
    <?= $form->field($model, 'category_id')->dropDownList( 
                        $arr_data
                    )->label('Родительская категория') ?>
    
    <?php
    if( empty($model->imgfile_name) ):?>
        <?= $form->field($model, 'file_mainphoto_tag')->fileInput()->label('Изображение') ?>
    <?php
    else: ?>
        <?= $form->field($model, 'imgfile_name')->input('hidden')->label('Изображение') ?>
        <img src="/uploads/goods/250/<?=$model->imgfile_name?>" alt="Photo">
        <br>
        <input type="submit" name="file_mainphoto_delete" value="Удалить фото">
        <br><br>
    <?php
    endif;?>
    
    <?= $form->field($model, 'description')->widget(CKEditor::className(),[
                                                'editorOptions' => ElFinder::ckeditorOptions('elfinder',[]),
                                                /*
                                                'editorOptions' => [
                                                'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                                                'inline' => false, //по умолчанию false
                                                ],*/
                                               ])
                                ->label('Описание'); ?>
    <?= $form->field($model, 'seo_keywords')->textarea(['rows' => 6, 'cols' => 5])->label('SEO. Ключевые слова') ?>
    <?= $form->field($model, 'seo_description')->textarea(['rows' => 6, 'cols' => 5])->label('SEO. Описание страницы') ?>
    
    <?= $form->field($model, 'is_hit')->dropDownList( 
                        [1 => 'Да', 
                          0 => 'Нет'],
                    )->label('Хит продаж') ?>
    
    <?= $form->field($model, 'is_new')->dropDownList( 
                        [1 => 'Да', 
                          0 => 'Нет'],
                    )->label('Новинка') ?>
    
    <?= $form->field($model, 'active')->dropDownList( 
                        [1 => 'Активен', 
                          0 => 'НЕ Активен'],
                    )->label('Статус') ?>

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
