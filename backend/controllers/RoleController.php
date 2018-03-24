<?php

namespace backend\controllers;

use backend\models\AuthItem;
use yii\helpers\ArrayHelper;

class RoleController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        $models=AuthItem::find()->all();
        //创建auth对象
        $auth=\Yii::$app->authManager;
        //找到所有权限
        $roles=$auth->getRoles();
        return $this->render('index',compact('roles'));
    }

    /*添加
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model=new AuthItem();
        //得到所有权限
         //创建auth对象
        $auth=\Yii::$app->authManager;
        $per=$auth->getPermissions();
        $per=ArrayHelper::map($per,'name','description');
//        var_dump($per);exit;

        //判断
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
//            var_dump($model->permissions);exit;

            //设置角色
            $role=$auth->createRole($model->name);

            //设置描述
            $role->description=$model->description;

            //权限入库
            if ($auth->add($role)) {

                //判断是否有权限
                if($model->permissions){
                    //给角色添加权限
                    foreach($model->permissions as $perName){
                        //通过权限名得到权限值
                        $per=$auth->getPermission($perName);
                        $auth->addChild($role,$per);
                    }
                }

                \Yii::$app->session->setFlash("success","角色添加成功");
                return $this->refresh();
            }
        }
        return $this->render('add',compact('model','per'));
    }

    /**编辑
     * @param $name
     * @return string|\yii\web\Response
     */
    public function actionEdit($name){
        $model=AuthItem::findOne($name);
        //得到所有权限
        //创建auth对象
        $auth=\Yii::$app->authManager;
        $per=$auth->getPermissions();
        $per=ArrayHelper::map($per,'name','description');
//        var_dump($per);exit;

        //得到当前角色所对应的权限
        $rolePer=$auth->getPermissionsByRole($name);
//        var_dump(array_keys($rolePer));exit;
        $model->permissions=array_keys($rolePer);

        //判断
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
//            var_dump($model->permissions);exit;

            //得到角色
            $role=$auth->getRole($model->name);

            //设置描述
            $role->description=$model->description;

            //权限入库
            if ($auth->update($model->name,$role)) {
                 //删除当前角色所有权限
                $auth->removeChildren($role);
                //判断是否有权限
                if($model->permissions){
                    //给角色添加权限
                    foreach($model->permissions as $perName){
                        //通过权限名得到权限值
                        $per=$auth->getPermission($perName);
                        $auth->addChild($role,$per);
                    }
                }

                \Yii::$app->session->setFlash("success","角色编辑成功");
                return $this->redirect(['role/index']);
            }
        }
        return $this->render('edit',compact('model','per'));
    }

    /**删除
     * @param $name
     */
    public function actionDel($name){
        //创建auth对象
        $auth=\Yii::$app->authManager;
        //找到权限
        $role=$auth->getRole($name);
        if ($auth->remove($role)) {
            \Yii::$app->session->setFlash("success","角色删除成功");
            return $this->redirect(['role/index']);
        }
    }

    //给用户添加一个角色
    public function actionAdminRole($roleName,$id){
        //实例化对象
        $auth=\Yii::$app->authManager;
        //通过角色名找到角色对象
        $role=$auth->getRole($roleName);

        //把用户指派给权限
        var_dump($auth->assign($role,$id));
    }

    //判断当前用户有没有权限
    public function actionCheck(){
        var_dump(\Yii::$app->user->can('goods/add'));
    }
}
