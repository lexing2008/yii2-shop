<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\Sections;


/**
 * Site controller
 */
class SectionsController extends Controller
{
    /**
     * Просмотр страницы
     *
     * @return mixed
     */
    public function actionView($tid)
    {
        $model = Sections::find()->where(['tid' => $tid])->one();
        //echo '<pre>';
        //print_r($model);
        return $this->render('view', ['model' => $model]);
    }
}
