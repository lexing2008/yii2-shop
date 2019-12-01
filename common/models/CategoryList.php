<?php
namespace common\models;

use Yii;


/**
 * Category model
 *
 */
class CategoryList {
    /**
     * категориии  расположенные как в БД
     * @var type 
     */
    public $original;
    
    public $category;
    /**
     * упорядоченные по дереву элементы
     * @var type 
     */
    public $items;
    
    /**
     *  количество элементов в $items. Вычисляется как счетчик. используется как счетчик
     * @var type 
     */
    protected $current_count_items = 0;
    
    /**
     * имя таблицы
     * @var type 
     */
    protected $table_name;
    
    /**
     * учитывать только активные
     * @var type 
     */
    protected $active;
    
    /**
     * поля таблицы
     * @var type 
     */
    protected $table_fields;
    
    /**
     * Родительская категория
     * @var type 
     */
    protected $parent_id = 0;
    
    protected $language;
    

    /**
     *
     * @param type $table_name название таблицы хранения списка категорий
     * @param type $language язык категорий
     * @param type $active учитывать только активные элементы (поле active в записи 1)
     * @param type $auto_load_from_table автоматическая загрука из БД при создании объекта
     * @param type $table_fields извлекаемые поля. Как в SQL: `id`, `name`, `title`
     */
    public function __construct($table_name, $language = 'ru_RU', $active = true, $auto_load_from_table = true, $table_fields = '',
                $parent_id = 0
    ) {
        $this->table_name = $table_name;
        $this->language = $language;
        $this->active = $active;
        $this->parent_id = $parent_id;

        if (empty($table_fields))
            $this->table_fields = '`id` , `pid` , `title`, `tid`, `active`, `position`';
        else
            $this->table_fields = $table_fields;

        // подгружаем всю информацию
        if ($auto_load_from_table)
            $this->load_from_table();
    }


    /**
     *   загрузка информации из БД и формирование правильной структуры
     * 
     * @throws ExceptionDB
     */
    public function load_from_table() {
        
        $arr_where = [];
        $arr_where['language'] = $this->language;

        
        // если атолько активные, то доопределяем строку запроса
        if ($this->active)
            $arr_where['active'] = 1;

        if ($this->parent_id)
            $arr_where['pid'] = $this->parent_id;

        $records = Category::find()->where( $arr_where )->orderBy('position ASC')->asArray()->all();
/*        echo '<pre>';
        print_r( $records );
        print_r( $arr_where );
*/
        $rows = count($records);
        unset($this->category);
        unset($this->original);
        unset($this->items);
        $this->category = [];
        for ($i = 0; $i < $rows; $i++) {
            $this->category[$records[$i]['id']] = $records[$i];
        }

        $this->original = $this->category;
        // устанавливаем текущее количество в нуль
        $this->current_count_items = 0;
        $this->next_item( $this->parent_id ); // (Родитель) pid = 0; (Уровень вложенности) level = 0; Начинаем отсчет уровня вложенности	
    }
    
    
    /**
     *  функция находит все элементы родителя
     * @param int $pid идентификатор родителя. Для самого верхнего это 0
     * @param int $level
     */
    private function next_item($pid, $level = 0) {
        // просматриваем весь массив
        foreach ($this->category as $key => $val) {
            // элемент пренадлежит родителю
            if ($val['pid'] == $pid) {
                // добавляем текущий элемент в наш массив упорядоченных элементов
                $this->items[$this->current_count_items] = $val;
                $this->items[$this->current_count_items]['level'] = $level;
                // удаляем текущий элемент из массива
                unset($this->category[$key]);
                // увеличивае счетчик
                $this->current_count_items++;
                // рекурсивно ищем потомков для данного
                $this->next_item($key, $level + 1);
            }
        }
    }
    
    /*
     * Возвращает данные для dropDownList
     */
    public function get_data_for_drop_down_list(){
         //  
        $data = ['0' => '..родительская категория'];
        // просматриваем весь массив
        foreach ($this->items as $key => $item) {
            $data[ $item['id'] ] = str_repeat('- - ', $item['level']) . $item['title'];
        }
        return $data;
    }
    

    /** возвращает массив потомков заданного элемента
     *
     * @param int $parent - идентификатор родителя. Приведен к int.
     * @param bool $only_active получить только активные элементы
     * @return array массив потомков
     */
    public function get_children(int $parent, $only_active = false) {
        $arr = array();
        $flag_level = 0;
        for ($i = 0; $i < count($this->items); $i++) {
            if ($this->items[$i]['id'] == $parent) {
                $flag_level = $this->items[$i]['level'];
                $i++;
                while ($i < count($this->items) && $this->items[$i]['level'] > $flag_level) {
                    if ($only_active && $this->items[$i]['active'])
                        $arr[] = $this->items[$i];
                    elseif ($only_active == false)
                        $arr[] = $this->items[$i];

                    $i++;
                }
                return $arr;
            }
        }
        return $arr;
    }
}
