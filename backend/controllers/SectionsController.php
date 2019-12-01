<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Sections;
use yii\data\ActiveDataProvider;


/**
 * Sections controller
 */
class SectionsController extends Controller
{
    
   
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'update', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Sections::find()->where(['status' => 1]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Добавление пользователя
     *
     * @return string
     */
    public function actionCreate()
    {
        $model = Sections::findOne(['status' => 0]);
        if( empty($model)  ){
            $model = new Sections;
            $model->insert();
            $model->save(false);
        }
        return $this->redirect(['update', 'id' => $model->id]);
    }
    
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Sections::findOne($id);

        if ( $model->load(Yii::$app->request->post()) && $model->save() ) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}
