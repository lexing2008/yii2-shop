<?php

namespace Components;

use yii\base\Widget;
use common\models\CategoryList;

/**
 * MenuWidget
 *
 * @author Lexing
 */
class MenuWidget extends Widget{
    
    public $current_category_id;


    public function init( ) {
        
    }
    
    public function run(): string {
        parent::run();
        
        $catlist = new CategoryList('category');
        
        $items = $catlist->items;
        
        return $this->render('menu', 
                        ['items' => $items, 'current_category_id' => $this->current_category_id] );
    }
}
