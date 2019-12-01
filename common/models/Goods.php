<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\GoodsUploadMainPhoto;
use yii\web\UploadedFile;
use Components\Tof\Image\Image;


/**
 * Category model
 *
 */
class Goods extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const IMAGE_FOLDER = '/uploads/goods/';
    const IMAGE_QUALITY = 80;
    const IMAGE_THUMBS = [
        '100', '250', '450'
    ];

    public $file_mainphoto_tag;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['page_title', 'title'], 'required' ],
                [['price', 'discount'], 'trim'],
                [['price', 'discount'], 'double'],
                [['description', 'seo_description', 'seo_keywords', 'active', 'category_id', 'is_hit', 'is_new'], 'safe' ],
                [['file_mainphoto_tag'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, gif, jpg', 'maxSize' => 2*1024*1024],
                [['file_mainphoto_tag'], 'image', 'maxWidth' => 3000, 'maxHeight' => 3000],
                ];
    }
    
    public function beforeSave($insert) {
        if( $this->validate() )
            $this->status = 1;
        return parent::beforeSave($insert);
    }
    
    
    public function upload()
    {
        if ( $this->file_mainphoto_tag->baseName ) {
            $path = $_SERVER['DOCUMENT_ROOT'] . self::IMAGE_FOLDER  ;
            $file_name = $this->id . '.' . $this->file_mainphoto_tag->extension;
            $fin = $path . $file_name;
            $this->file_mainphoto_tag->saveAs( $fin );

            // миниатюры
            foreach (self::IMAGE_THUMBS as $key => $thumbs) {
                $fout = $path . $thumbs . '/' . $file_name;
                Image::scale($fout, $fin, $thumbs, $thumbs, self::IMAGE_QUALITY);
            }

            $model_photo = self::findOne( $this->id  );
            $this->imgfile_name = $model_photo->imgfile_name = $file_name;
            $model_photo->save(false);
            return true;
        } else {
            return false;
        }
    }
    
    public function beforeLoad(){
        if (Yii::$app->request->isPost) {
            $this->file_mainphoto_tag = UploadedFile::getInstance($this, 'file_mainphoto_tag');
            if ($this->upload()) {
                // file is uploaded successfully

            }
        }
    }

    public function afterLoad(){

        if( Yii::$app->request->post('file_mainphoto_delete') ){
            if( $this->imgfile_name ){
                // удаляем фото
                $model_photo = self::findOne( $this->id );
                $path = $_SERVER['DOCUMENT_ROOT'] . self::IMAGE_FOLDER . $model_photo->imgfile_name; 
                if(file_exists($path))
                    unlink($path);
                foreach (self::IMAGE_THUMBS as $key => $thumbs) {
                    $path = $_SERVER['DOCUMENT_ROOT'] . self::IMAGE_FOLDER . $thumbs .  '/' . $model_photo->imgfile_name; 
                    if(file_exists($path))
                        unlink($path);
                }

                $this->imgfile_name = $model_photo->imgfile_name = '';
                $model_photo->save();
            }
        }
    }
}