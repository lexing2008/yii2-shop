<?php

namespace Components\Validators;

use yii\validators\Validator;


/**
 * Description of CategoryValidator
 *
 * @author Lexing
 */
class CategoryValidator extends Validator{

    public function validateAttribute($model, $attribute) {
        parent::validateAttribute($model, $attribute);
        if( $model->id == $model->$attribute){
            $this->addError($model, $attribute, 'Выьерите другую категорию');
        }
    }
    
}
