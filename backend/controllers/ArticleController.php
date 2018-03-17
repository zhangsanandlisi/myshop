<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleContent;
use PHPUnit\Framework\Constraint\Count;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Article::find();

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
        $model=new Article();
        //创建文章内容对像
        $content=new ArticleContent();

        //分列数据
        $cate=ArticleCategory::find()->all();
        //二维转一维
        $cateArr=ArrayHelper::map($cate,"id","name");
//  var_dump($catesArr);exit;
        $request=new Request();

        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->save()) {

                    //处理文章内容
                    $content->load($request->post());
                    $content->article_id=$model->id;
                    if ($content->save()) {
                        \Yii::$app->session->setFlash("success","添加成功");
                        return $this->redirect(['index']);
                    }

                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render('add',compact("model","cateArr","content"));
    }


    public function actionEdit($id){
        $model=Article::findOne($id);

        $content=ArticleContent::findOne($id);

        //分列数据
        $cate=ArticleCategory::find()->all();
        //二维转一维
        $cateArr=ArrayHelper::map($cate,"id","name");

        $request=new Request();

        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->save()) {

                    //处理文章内容
                    $content->load($request->post());
                    $content->article_id=$model->id;
                    if ($content->save()) {
                        \Yii::$app->session->setFlash("success","编辑成功");
                        return $this->redirect(['index']);
                    }

                    \Yii::$app->session->setFlash("success","编辑成功");
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render('add',compact("model","cateArr","content"));
    }

    public function actionDel($id){
        if (Article::findOne($id)->delete()) {
            ArticleContent::findOne($id)->delete();
            \Yii::$app->session->setFlash("success","删除成功");
            return $this->redirect(['index']);
        }
    }

    //end
    public function actionEnd($id){
        $model=Article::findOne($id);
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash("success","禁用成功");
        return $this->redirect(['index']);
    }

    //start
    public function actionStart($id){
        $model=Article::findOne($id);
        $model->status=1;
        $model->save();
        \Yii::$app->session->setFlash("success","激活成功");
        return $this->redirect(['index']);
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}
