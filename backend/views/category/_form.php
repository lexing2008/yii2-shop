<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CategoryList;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

$catlist = new CategoryList('category');
$arr_data = $catlist->get_data_for_drop_down_list();
//echo '<pre>';
//print_r($arr_data);

?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'] ]); ?>

    <?= $form->field($model, 'page_title')->textInput(['maxlength' => true])->label('SEO. Заголовок страницы') ?>
    <?= $form->field($model, 'title')->textInput()->label('Название категории') ?>
    <?= $form->field($model, 'tid')->textInput()->label('Название в URL') ?>
    <?= $form->field($model, 'pid')->dropDownList( 
                        $arr_data
                    )->label('Родительская категория') ?>
    
    <?= $form->field($model, 'seo_text')->widget(CKEditor::className(),[
                                                'editorOptions' => ElFinder::ckeditorOptions('elfinder',[]),
                                                /*
                                                'editorOptions' => [
                                                'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                                                'inline' => false, //по умолчанию false
                                                ],*/
                                               ])
                                ->label('SEO. Текст'); ?>
    <?= $form->field($model, 'seo_keywords')->textarea(['rows' => 6, 'cols' => 5])->label('SEO. Ключевые слова') ?>
    <?= $form->field($model, 'seo_description')->textarea(['rows' => 6, 'cols' => 5])->label('SEO. Описание страницы') ?>
    <?= $form->field($model, 'active')->dropDownList( 
                        [1 => 'Активна', 
                          0 => 'НЕ Активна'],
                    )->label('Статус') ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
