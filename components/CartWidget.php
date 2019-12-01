<?php

namespace Components;

use yii\base\Widget;

/**
 * MenuWidget
 *
 * @author Lexing
 */
class CartWidget extends Widget{
    

    public function init( ) {
        
    }
    
    public function run(): string {
        parent::run();
        
        return $this->render('cart',
                        [] );
    }
}
