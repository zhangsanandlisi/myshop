<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use PHPUnit\Framework\Constraint\Count;
use yii\data\Pagination;
use yii\web\Request;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=ArticleCategory::find();

        $count=$query->count();
        //分页对象
        $page=new Pagination([
            'pageSize'=>3,
            'totalCount' => $count
        ]);
        //查询数据
        $models=$query->offset($page->offset)->limit($page->limit)->orderBy(['sort'=>'desc'])->all();

        return $this->render('index',compact("models","page"));
    }

    public function actionAdd(){
        $model=new ArticleCategory();

        $request=new Request();

        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->save()) {
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);
            }
        }

        return $this->render("add",compact("model"));
    }

    public function actionEdit($id){
        $model=ArticleCategory::findOne($id);

        $request=new Request();

        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->save()) {
                    \Yii::$app->session->setFlash("success","编辑成功");
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render("add",compact("model"));
    }


    public function actionDel($id){
        if (ArticleCategory::findOne($id)->delete()) {
            \Yii::$app->session->setFlash("success","删除成功");
            return $this->redirect(['index']);
        }
    }

   //end status
    public function actionEnd($id){
        $model=ArticleCategory::findOne($id);
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash("success","禁用成功");
        return $this->redirect(['index']);

    }

    //start status
    public function actionStart($id){
        $model=ArticleCategory::findOne($id);
        $model->status=1;
        $model->save();
        \Yii::$app->session->setFlash("success","激活成功");
        return $this->redirect(['index']);

    }

    //end2 is_help
    public function actionEnd2($id){
        $model=ArticleCategory::findOne($id);
        $model->is_help=0;
        $model->save();
        \Yii::$app->session->setFlash("success","禁用成功");
        return $this->redirect(['index']);

    }

    //start2 is_help
    public function actionStart2($id){
        $model=ArticleCategory::findOne($id);
        $model->is_help=1;
        $model->save();
        \Yii::$app->session->setFlash("success","激活成功");
        return $this->redirect(['index']);

    }
}
