<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Category;
use common\models\CategoryList;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;


/**
 * Category controller
 */
class CategoryController extends Controller
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
     
        $catlist = new CategoryList('category');

        $dataProvider = new ArrayDataProvider([
            'allModels' => $catlist->items,
            'pagination' => [
                'pageSize' => 20,
            ],
            /*
            'sort' => [
                'attributes' => ['id', 'name'],
            ],*/
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
        $model = Category::findOne(['status' => 0]);
        if( empty($model)  ){
            $model = new Category;
            $model->status = 0;
            $model->active = 1;
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
        $model = Category::findOne($id);
        
        if ( $model->load(Yii::$app->request->post()) && $model->save() ) {

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}
