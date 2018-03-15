<?php

namespace backend\controllers;

use backend\models\Brand;
use Codeception\Lib\Generator\PageObject;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Brand::find();

        //总数据数
        $count=$query->count();
        //声明一个分页对象
        $page=new Pagination([
            'pageSize' => 5,
            'totalCount' => $count
        ]);
        //查询数据
        $models=$query->offset($page->offset)->limit($page->limit)->orderBy(['sort'=>'desc'])->all();

        return $this->render('index',compact("models","page"));
    }

    //增
    public function actionAdd(){
        //创数据模型
        $model=new Brand();

        //创request
        $request=new Request();
        if ($request->isPost) {
            //绑定数据
            $model->load($request->post());

            //接收图片
            $model->imgFile=UploadedFile::getInstance($model,"imgFile");
            //定义一个空的
            $path="";
            if ($model->imgFile) {
                //定义路径
                $path="images/".time().".".$model->imgFile->extension;
                //移动到新目录
                $model->imgFile->saveAs($path,false);
            }

            //后台验证
            if ($model->validate()) {
                $model->logo=$path;
                //保存数据
                if ($model->save(false)) {
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(["index"]);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render('add',compact("model"));
    }

    //改
    public function actionEdit($id){
        //找到数据
        $model=Brand::findOne($id);

        //创request
        $request=new Request();
        if ($request->isPost) {
            //绑定数据
            $model->load($request->post());

            //接收图片
            $model->imgFile=UploadedFile::getInstance($model,"imgFile");
            //定义一个空的
              $path="";
            if ($model->imgFile) {
                //定义路径
                $path="images/".time().".".$model->imgFile->extension;
                //移动到新目录
                $model->imgFile->saveAs($path,false);
            }

            //后台验证
            if ($model->validate()) {
                if($path){
                    $model->logo=$path;
                }
                //保存数据
                if ($model->save(false)) {
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(["index"]);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render('add',compact("model"));
    }

    //软删
    public function actionDel($id)
    {
        //找到数据
        $model = Brand::findOne($id);
        //重新复值
        $model->status=0;
        //保存数据
         $model->save();
        \Yii::$app->session->setFlash("success", "禁用成功");
        return $this->redirect(["index"]);


    }
}
