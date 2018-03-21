<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AdminForm;
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

        $request=new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->password=\Yii::$app->security->generatePasswordHash($model->password);
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
        if (Admin::findOne($id)->delete()) {
            \Yii::$app->session->setFlash("success","删除成功");
            return $this->redirect(['index']);
        }
    }

}
