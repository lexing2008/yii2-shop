<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'] ]); ?>

    <?= $form->field($model, 'page_title')->textInput(['maxlength' => true])->label('SEO. Заголовок страницы') ?>
    <?= $form->field($model, 'title')->textInput()->label('Название раздела') ?>
    <?= $form->field($model, 'tid')->textInput()->label('Название в URL') ?>

    
    <?= $form->field($model, 'text')->widget(CKEditor::className(),[
                                                'editorOptions' => ElFinder::ckeditorOptions('elfinder',[]),
                                               ])
                                ->label('Текст'); ?>
    <?= $form->field($model, 'seo_keywords')->textarea(['rows' => 6, 'cols' => 5])->label('SEO. Ключевые слова') ?>
    <?= $form->field($model, 'seo_description')->textarea(['rows' => 6, 'cols' => 5])->label('SEO. Описание страницы') ?>
    <?= $form->field($model, 'active')->dropDownList( 
                        [1 => 'Активен', 
                          0 => 'НЕ Активен'],
                    )->label('Статус') ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
