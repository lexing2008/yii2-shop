<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use Components\Validators\CategoryValidator;


/**
 * Category model
 *
 */
class Category extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['page_title', 'title', 'tid'], 'required' ],
                ['pid', 'validateCategoryPid'],
                ['tid', 'validateCategoryTid'],
                ['tid', 'trim'],
                [['seo_text', 'seo_description', 'seo_keywords', 'active', 'pid'], 'safe' ],
                ];
    }
    
    public function beforeSave($insert) {
        $this->status = 1;
        $this->language = 'ru_RU';
        return parent::beforeSave($insert);
    }
    
    public function validateCategoryPid($attribute, $params){
        if( $this->id == $this->pid ){
            $this->addError($attribute, 'Измените родительскую категорию');
        }
    }

    public function validateCategoryTid($attribute, $params){
        $users = self::find()->select('COUNT(*) as `count`')->where(['tid' => $this->tid])->andWhere( ['!=', 'id', $this->id])->asArray()->one();

        if( $users['count'] ){
            $this->addError($attribute, 'Категория с таким URL уже существует');
        }
    }
}