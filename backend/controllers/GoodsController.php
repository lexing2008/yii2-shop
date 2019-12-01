<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Goods;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class GoodsController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            'query' => Goods::find()->where(['status' => 1]),
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
        $model = Goods::findOne(['status' => 0]);
        if( empty($model)  ){
            $model = new Goods;
            $model->status = 0;
            $model->active = 1;
            $model->price = 0;
            $model->discount = 0;
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
        $model = Goods::findOne($id);

                
        $model->beforeLoad();
        $load = $model->load(Yii::$app->request->post());
        $model->afterLoad();

        if ( $load && $model->save() ) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}
