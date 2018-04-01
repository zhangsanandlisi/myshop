<?php

namespace frontend\controllers;

use frontend\components\GoodsCart;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\User;
use Mrgoon\AliSms\AliSms;
use yii\helpers\Json;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    //验证码
    public function actions()
    {
        return [
            'code' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'maxLength' => 4,
                'minLength' => 4,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionReg(){

        $request=\Yii::$app->request;
        if($request->isPost){
//            var_dump($request->post());exit;
//            echo "<pre>";

            $user=new User();
            //场景
            $user->setScenario(User::SCENARIO_REGISTER);
            //数据绑定
            $user->load($request->post());
//            $user->mobile;exit;
            if ($user->validate()) {

                $user->auth_key=\Yii::$app->security->generateRandomString();
                $user->password_hash=\Yii::$app->security->generatePasswordHash($request->post('password'));
                if ($user->save(false)) {
                    $result=[
                        'status'=>1,
                        'msg'=>'注册成功',
                        'data'=>"",
                    ];
                    return Json::encode($result);
                }
            }else{
                $result=[
                    'status'=>0,
                    'msg'=>'注册失败',
                    'data'=>$user->errors,
                ];
                return Json::encode($result);
            }
//            var_dump($user);
//            $user->load($request->post());
//
//            var_dump($user);exit;
//
//            $user->username=$request->post('username');
//            $user->password_hash=\Yii::$app->security->generatePasswordHash($request->post('password'));
//            $user->email=$request->post('email');
//            $user->mobile=$request->post('tel');
        }
//        $user->save();


        return $this->render('reg');
    }


    public function actionSendSms($mobile){
        //生成验证码
        $code=rand(100000,999999);
        //发送验证码给手机
        $config = [
            'access_key' => 'LTAIntepXAqhr1ub',
            'access_secret' => 'lM4LByllDVe2rkKWk0Nadegt78pq2Z',
            'sign_name' => '马英杰',
        ];
        $aliSms = new AliSms();
        $response = $aliSms->sendSms($mobile, 'SMS_128636099', ['code'=> $code], $config);
        if ($response->Message=="OK") {
             //把code保存到session
            $session=\Yii::$app->session;
            $session->set("tel".$mobile,$code);//mobile键名，code值

            //测试
            return $code;
        }else{
            var_dump($response->Message);
        }

    }

//    public function actionCheckSms                                                                                                                                                                                                                                                                                                                                                                  ($mobile,$code){
//        //通过手机号取出之前发送的code
//        $oldCode=\Yii::$app->session->get("tel".$mobile);
//        //判断code是否正确
//        if ($code==$oldCode) {
//            echo "ok";
//        }else{
//            echo "no";
//        }
//    }

      //登录
    public function actionLogin(){

        $request=new Request();
        if ($request->isPost) {
            $model=new User();
            //设置场景
            $model->setScenario(User::SCENARIO_LOGIN);
            //绑定数据
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                //对比用户名
                $user=User::findOne(['username'=>$model->username]);
                //判断
                if($user && \Yii::$app->security->validatePassword($model->password,$user->password_hash)){
                    $user->login_ip=$_SERVER["REMOTE_ADDR"];
                    $user->login_time=time();
                     \Yii::$app->user->login($user,$model->rememberMe?3600*24:0);

                     //数据同步
                    (new GoodsCart())->dbSyn()->flush()->save();
                    //
//                     //取出cookie中的数据
//                    $cart=(new GoodsCart())->get();
//                    //把数据同步到数据库
//                    $userId=\Yii::$app->user->id;
//                    foreach($cart as $goodsKey=>$amount){
//                        $cartDb=Cart::findOne(['goods_id'=>$goodsKey,'user_id'=>$userId]);
//                        if($cartDb){
//                            //修改
//                            $cartDb->amount+=$amount;
//
//                        }else{
//                            //创建一个新的对象
//                            $cartDb=new Cart();
//                            //赋值
//                            $cartDb->goods_id=$goodsKey;
//                            $cartDb->user_id=$userId;
//                            $cartDb->amount=$amount;
//
//                        }
//                        //保存
//                        $cartDb->save();
//                    }

                    $result=[
                        'status'=>1,
                        'msg'=>'登陆成功',
                        'data'=>null
                    ];
                    return Json::encode($result);
                }else{
                    $result=[
                        'status'=>0,
                        'msg'=>'用户名或密码错误',
                        'data'=>null
                    ];
                    return Json::encode($result);
                }
            }else{
                $result=[
                    'status'=>0,
                    'msg'=>'输入有误',
                    'data'=>$model->errors
                ];
                return Json::encode($result);
            }

        }
        return $this->render('login');
    }

    //登出
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

}
