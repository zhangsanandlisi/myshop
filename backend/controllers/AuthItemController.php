<?php

namespace backend\controllers;

use backend\models\AuthItem;

class AuthItemController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        $models=AuthItem::find()->all();
        //创建auth对象
        $auth=\Yii::$app->authManager;
        //找到所有权限
        $pers=$auth->getPermissions();
        return $this->render('index',compact('pers'));
    }

    /*添加
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model=new AuthItem();
        //判断
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
//            var_dump($model->name);exit;
            //创建auth对象
            $auth=\Yii::$app->authManager;

            //设置权限
            $per=$auth->createPermission($model->name);

            //设置描述
            $per->description=$model->description;

            //权限入库
            if ($auth->add($per)) {
                \Yii::$app->session->setFlash("success","权限添加成功");
                return $this->refresh();
            }
        }
        return $this->render('add',compact('model'));
    }

    /*编辑
     * @param $name
     * @return string|\yii\web\Response
     */
    public function actionEdit($name){
        $model=AuthItem::findOne($name);
        //判断
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
//            var_dump($model->name);exit;
            //创建auth对象
            $auth=\Yii::$app->authManager;

            //得到权限
            $per=$auth->getPermission($name);

            //设置描述
            $per->description=$model->description;

            //权限入库
            if ($auth->update($model->name,$per)) {
                \Yii::$app->session->setFlash("success","权限编辑成功");
                return $this->redirect(['auth-item/index']);
            }
        }
        return $this->render('edit',compact('model'));
    }

    /**删除
     * @param $name
     */
    public function actionDel($name){
        //创建auth对象
        $auth=\Yii::$app->authManager;
        //找到权限
        $per=$auth->getPermission($name);
        if ($auth->remove($per)) {
            \Yii::$app->session->setFlash("success","权限删除成功");
            return $this->redirect(['auth-item/index']);
        }
    }
}
