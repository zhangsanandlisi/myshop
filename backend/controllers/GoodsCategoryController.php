<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use tests\models\Tree;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Request;

class GoodsCategoryController extends \yii\web\Controller
{
    //显示
    public function actionIndex()
    {
        $query = GoodsCategory::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }


    //增
    public function actionAdd(){
        $model=new GoodsCategory();
        //查询所有数据并转换成json格式
        $all=GoodsCategory::find()->asArray()->all();

        //添加一组数据
        $all[]=['id'=>0,'name'=>'顶级分类','parent_id'=>0];

        $allJson=Json::encode($all);

        $request=new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {

                //判断parent_id
                if($model->parent_id==0){
                    //创建一级分类
                    $model->makeRoot();
                    \Yii::$app->session->setFlash("success","创建成功");
                    return $this->refresh();
                }else{
                    //找到上级分类id
                    $cateParent=GoodsCategory::findOne($model->parent_id);
                    //创建下级分类
                    $model->prependTo($cateParent);
                    \Yii::$app->session->setFlash("success","创建成功");
                    return $this->refresh();
                }

            }else{
                var_dump($model->errors);
            }
        }

        return $this->render("add",compact("model","allJson"));
    }

    //改
    public function actionUpdate($id){
        $model=GoodsCategory::findOne($id);
        //查询所有数据并转换成json格式
        $all=GoodsCategory::find()->asArray()->all();

        //添加一组数据
        $all[]=['id'=>0,'name'=>'顶级分类','parent_id'=>0];

        $allJson=Json::encode($all);

        $request=new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {

                //判断parent_id
                if($model->parent_id==0){
                    //创建一级分类
                    $model->makeRoot();
                    \Yii::$app->session->setFlash("success","创建成功");
                    return $this->refresh();
                }else{
                    //找到上级分类id
                    $cateParent=GoodsCategory::findOne($model->parent_id);
                    //创建下级分类
                    $model->prependTo($cateParent);
                    \Yii::$app->session->setFlash("success","创建成功");
                    return $this->refresh();
                }

            }else{
                var_dump($model->errors);
            }
        }

        return $this->render("add",compact("model","allJson"));
    }

    //删
    public function actionDelete($id)
    {
        $model = GoodsCategory::findOne($id);
        if ($model->delete()) {
            \Yii::$app->session->setFlash("success","删除成功");
            return $this->redirect(['index']);
        }
    }
}
