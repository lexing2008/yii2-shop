<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use backend\models\UserUploadPhoto;
use Components\Tof\Image\Image;
use yii\web\UploadedFile;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    const IMAGE_FOLDER = '/uploads/user/';
    const IMAGE_QUALITY = 80;
    const IMAGE_THUMBS = [
        '100', '200', '450'
    ];

    /**
     * @var UploadedFile
     */
    public $file_photo_tag;
    public $password;

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }
    
    public function upload()
    {
        if ($this->validate() && $this->file_photo_tag->baseName ) {
            $path = $_SERVER['DOCUMENT_ROOT'] . self::IMAGE_FOLDER  ;
            $file_name = $this->id . '.' . $this->file_photo_tag->extension;
            $fin = $path . $file_name;
            $this->file_photo_tag->saveAs( $fin );

            // миниатюры
            foreach (self::IMAGE_THUMBS as $key => $thumbs) {
                $fout = $path . $thumbs . '/' . $file_name;
                Image::scale($fout, $fin, $thumbs, $thumbs, self::IMAGE_QUALITY);
            }

            $model_photo = self::findOne( $this->id  );
            $this->file_photo = $model_photo->file_photo = $file_name;
            $model_photo->save();
            return true;
        } else {
            return false;
        }
    }
    
    public function beforeLoad(){
        if (Yii::$app->request->isPost) {
            $this->file_photo_tag = UploadedFile::getInstance($this, 'file_photo_tag');
            if ($this->upload()) {
                // file is uploaded successfully

            }
        }
    }

    public function afterLoad(){

        if( Yii::$app->request->post('file_photo_delete') ){
            if( $this->file_photo ){
                // удаляем фото
                $model_photo = self::findOne( $this->id );
                $path = $_SERVER['DOCUMENT_ROOT'] . self::IMAGE_FOLDER . $model_photo->file_photo; 
                if(file_exists($path))
                    unlink($path);
                foreach (self::IMAGE_THUMBS as $key => $thumbs) {
                    $path = $_SERVER['DOCUMENT_ROOT'] . self::IMAGE_FOLDER . $thumbs .  '/' . $model_photo->file_photo; 
                    if(file_exists($path))
                        unlink($path);
                }

                $this->file_photo = $model_photo->file_photo = '';
                $model_photo->save();
            }
        }
    }
    
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ ['username', 'email', 'status'], 'required'],
            [ ['password'], 'safe' ],
            ['username', 'validateUsername'],
            ['email', 'validateEmail'],
            [['file_photo_tag'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, gif, jpg', 'maxSize' => 2*1024*1024],
            [['file_photo_tag'], 'image', 'maxWidth' => 3000, 'maxHeight' => 3000],
            
        ];
    }
    
    /**
     * Триггер выполняемый перед сохранением модели
     * @param bool $insert
     */
    public function beforeSave($insert) {
        if( $this->isNewRecord ){
            $this->created_at = time();
        }
        $this->updated_at = time();
        if( !empty($this->password) )
            $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        // генерируем авторизационный ключ
        $this->generateAuthKey();
        $this->generateEmailVerificationToken();
        return parent::beforeSave($insert);
    }

    /**
     * Триггер выполняемый после сохранения модели
     * @param bool $insert
     */
    public function afterSave($insert, $changedAttributes) {
        $this->password_hash = '';
        return parent::afterSave($insert, $changedAttributes);
    }

    public function validateUsername($attribute, $params){
        $users = self::find()->select('COUNT(*) as `count`')->where(['username' => $this->username])->andWhere( ['!=', 'id', $this->id])->asArray()->one();

        if( $users['count'] ){
            $this->addError($attribute, 'Пользователь с таким именем уже существует');
        }
    }
    
    public function validateEmail($attribute, $params){
        $users = self::find()->select('COUNT(*) as `count`')->where(['email' => $this->email])->andWhere( ['!=', 'id', $this->id])->asArray()->one();

        if( $users['count'] ){
            $this->addError($attribute, 'Пользователь с таким Email уже существует');
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
