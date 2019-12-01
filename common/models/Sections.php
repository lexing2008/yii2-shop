<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use Components\Validators\CategoryValidator;


/**
 * Category model
 *
 */
class Sections extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sections';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['page_title', 'title', 'text', 'tid'], 'required' ],
                ['tid', 'validateSectionTid'],
                ['tid', 'trim'],
                [['seo_description', 'seo_keywords', 'active'], 'safe' ],
                ];
    }
    
    public function beforeSave($insert) {
        $this->status = 1;
        return parent::beforeSave($insert);
    }
    
    public function validateSectionTid($attribute, $params){
        $users = self::find()->select('COUNT(*) as `count`')->where(['tid' => $this->tid])->andWhere( ['!=', 'id', $this->id])->asArray()->one();

        if( $users['count'] ){
            $this->addError($attribute, 'Раздел с таким URL уже существует');
        }
    }
}