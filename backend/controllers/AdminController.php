<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AdminForm;
use common\models\LoginForm;
use yii\data\Pagination;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{
    //查
    public function actionIndex()
    {
        $query=Admin::find();
        $count=$query->count();
        $page=new Pagination([
            'totalCount' => $count,
            'pageSize' => 3
        ]);
        $models=$query->offset($page->offset)->limit($page->limit)->orderBy('create_time')->all();
        return $this->render('index',compact('models','page'));
    }

    //添加
    public function actionAdd(){
        $model=new Admin();

        $request=new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->password=\Yii::$app->security->generatePasswordHash($model->password);
                //设置令牌随机字符串32位
                $model->auto_key=\Yii::$app->security->generateRandomString();
//                var_dump( $model->auto_key);exit;
                if ($model->save()) {
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render('add',compact('model'));
    }
    //编辑
    public function actionEdit($id){
        $model=Admin::findOne($id);

        //设置场景
        $model->setScenario('edit');
        $password=$model->password;
//        var_dump($password);exit;


        $request=new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //场景 判断是否输入密码
            $model->password=$model->password?\Yii::$app->security->generatePasswordHash($model->password):$password;
                //设置令牌随机字符串32位
//                $model->auto_key=\Yii::$app->security->generateRandomString();
                if ($model->save()) {
                    \Yii::$app->session->setFlash("success","编辑成功");
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render('add',compact('model'));
    }
    //删
    public function actionDel($id){
       $model= Admin::findOne($id);
             if(\Yii::$app->user->identity->username!==$model->username){
            $model->delete();
            \Yii::$app->session->setFlash("success","删除成功");
            return $this->redirect(['index']);
    }else{
                 \Yii::$app->session->setFlash("danger","无法删除当前登录用户");
                 return $this->redirect(['index']);
             }
    }

    //登录
    public function actionLogin(){
        $model=new LoginForm();

//        $data=new Admin();

        $request=new Request();
        if ($request->isPost) {


            //绑定数据
            $model->load($request->post());
//            var_dump($model->rememberMe);exit;
            //通过username查找数据
            $admin=Admin::find()->where(['username'=>$model->username])->one();
            //判定admin是否存在
            if ($admin) {
                //对比密码
                if(\Yii::$app->security->validatePassword($model->password,$admin->password)){

//                     var_dump($admin->id);exit;
                    $ip=$_SERVER["REMOTE_ADDR"];
                    $admin->last_ip=$ip;
                    $admin->last_time=time();
//                    var_dump( $admin->last_ip, $admin->last_time);exit;
                    if ($admin->save()) {
                        //通过user组件登录
                        \Yii::$app->user->login($admin,$model->rememberMe?3600*24:0);
                        return $this->redirect(['admin/index']);
                    }else{
                        var_dump($admin->errors);exit;
                    }


                }else{
                    $model->addError('password','密码错误');
                }
            }else{
                $model->addError('username','用户名错误');
            }
        }

        return $this->render('login',compact('model'));
    }

    //登出
    public function actionLogout()
    {

        \Yii::$app->user->logout();


        return $this->redirect(['login']);
    }
}
