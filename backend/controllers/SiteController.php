<?php
namespace backend\controllers;

use backend\models\Admin;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\Request;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            return $this->goBack();
//        } else {
//            $model->password = '';
//
//            return $this->render('login', [
//                'model' => $model,
//            ]);
//        }
        $model=new LoginForm();

//        $data=new Admin();

        $request=new Request();
        if ($request->isPost) {


            //绑定数据
            $model->load($request->post());

            //通过username查找数据
            $admin=Admin::find()->where(['username'=>$model->username])->one();
            //判定admin是否存在
            if ($admin) {
                //对比密码
                if(Yii::$app->security->validatePassword($model->password,$admin->password)){

//                     var_dump($admin->id);exit;
                    $ip=$_SERVER["REMOTE_ADDR"];
                    $admin->last_ip=$ip;
                    $admin->last_time=time();
//                    var_dump( $admin->last_ip, $admin->last_time);exit;
                    if ($admin->save()) {
                        //通过user组件登录
                        \Yii::$app->user->login($admin);
                        return $this->redirect(['admin/index']);
                    }else{
                        var_dump($admin->errors);exit;
                    }


                }else{
                    \Yii::$app->session->setFlash("danger","密码错误");
                }
            }else{
                \Yii::$app->session->setFlash("danger","姓名错误");
            }
        }

        return $this->render('login',compact('model'));
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {

        Yii::$app->user->logout();


        return $this->goHome();
    }
}
