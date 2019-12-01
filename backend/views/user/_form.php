<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'] ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('Имя пользователя') ?>
    <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
    <?= $form->field($model, 'email')->input('email')->label('Электронная почта') ?>
    <?= $form->field($model, 'status')->dropdownList( 
                        [10 => 'Активен', 
                          9 => 'НЕ Активен'],
            )->label('Статус') ?>
    
    <?php
    if( empty($model->file_photo) ):?>
        <?= $form->field($model, 'file_photo_tag')->fileInput()->label('Фотография') ?>
    <?php
    else: ?>
        <?= $form->field($model, 'file_photo')->input('hidden')->label('Фотография') ?>
        <img src="/uploads/user/200/<?=$model->file_photo?>" alt="Photo">
        <br>
        <input type="submit" name="file_photo_delete" value="Удалить фото">
        <br><br>
    <?php
    endif;?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
