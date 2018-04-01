<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsImg;
use backend\models\GoodsIntro;
use crazyfd\qiniu\Qiniu;
use PHPUnit\Framework\Constraint\Count;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{

//    public function behaviors()
//    {
//        return [
//            'rbac'=>[
//                'class'=>RbacFilter::className()
//            ]
//        ];
//    }

    /*
     * 查
     */
    public function actionIndex()
    {
        $query=Goods::find();

        //获取数据
        $minPrice=\Yii::$app->request->get("minPrice");
        $maxPrice=\Yii::$app->request->get("maxPrice");
        $keyword=\Yii::$app->request->get("keyword");

       //加条件
        if($minPrice){
            $query->andWhere("price>={$minPrice}");
        }
        if($maxPrice){
            $query->andWhere("price<={$maxPrice}");
        }
        if($keyword!==""){
//            var_dump($keyword);exit;
            $query->andWhere("name like '%{$keyword}%' or sn like '%{$keyword}%'");
        }

        $count=$query->count();
        $page=new Pagination([
            'totalCount' => $count,
            'pageSize' => 3
        ]);
        $models=$query->offset($page->offset)->limit($page->limit)->orderBy("sort")->all();

        return $this->render('index',compact("models","page"));
    }

    /*
     * 增
     */
    public function actionAdd(){
        $model=new Goods();

        //创建文章内容对像
        $content=new GoodsIntro();

        //创建商品分类对象
        $cate=GoodsCategory::find()->orderBy('tree,left')->all();
        $cate=ArrayHelper::map($cate,'id','NameText');
//        echo "<pre/>";
//        var_dump($cate);exit;

        //查询brand数据
        $allBrand=Brand::find()->all();
        //一维转二维
        $brandArr=ArrayHelper::map($allBrand,"id","name");

        //查询goods数据
        $allGoods=GoodsCategory::find()->where(['depth'=>'1'])->all();
        //二维转一维
        $goodsArr=ArrayHelper::map($allGoods,"id","name");

        $request=new Request();
        if ($request->isPost) {
            $model->load($request->post());
//            var_dump($model->imgFiles);exit;
            if ($model->validate()) {

                if(!$model->sn){
                    //获取当天时间并转成时间戳
                    $date=strtotime(date("Ymd"));
//                    echo sprintf("%05d",1);
                     //获取数据库中大于当前时间的数据的数量
                    $count=Goods::find()->where(['>','create_time',$date])->count();
//                    echo $count;exit;
                    $count=$count+1;
                    $count="0000".$count;
                    $count=substr($count,-5);
                    //赋值
                    $model->sn=date("Ymd").$count+1;
                }

                if ($model->save(false)) {

                    //保存文章
                    $content->load($request->post());
                    $content->goods_id=$model->id;
                    if ($content->save()) {
                        \Yii::$app->session->setFlash("success","保存成功");
                    }

                    //保存多图
                    foreach ($model->images as $image){
                        //创建对象
                        $imgFiles=new GoodsImg();
                        //赋值
                        $imgFiles->goods_id=$model->id;
                        $imgFiles->path=$image;
                        //保存
                        $imgFiles->save();
                    }

                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(["index"]);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render("add",compact("model","brandArr","goodsArr","content","cate"));
    }

    /*
     * 编辑
     */
    public function actionEdit($id){
        $model=Goods::findOne($id);

        //创建文章内容对像
        $content=GoodsIntro::findOne(['goods_id'=>$id]);

        //创建商品分类对象
        $cate=GoodsCategory::find()->orderBy('tree,left')->all();
        $cate=ArrayHelper::map($cate,'id','NameText');

        //查询brand数据
        $allBrand=Brand::find()->all();
        //一维转二维
        $brandArr=ArrayHelper::map($allBrand,"id","name");

        //查询goods数据
        $allGoods=GoodsCategory::find()->where(['depth'=>'1'])->all();
        //二维转一维
        $goodsArr=ArrayHelper::map($allGoods,"id","name");

        $request=new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {

                if(!$model->sn){
                    //获取当天时间并转成时间戳
                    $date=strtotime(date("Ymd"));
//                    echo sprintf("%05d",1);
                    //获取数据库中大于当前时间的数据的数量
                    $count=Goods::find()->where(['>','create_time',$date])->count();
//                    echo $count;exit;
                    $count=$count+1;
                    $count="0000".$count;
                    $count=substr($count,-5);
                    //赋值
                    $model->sn=date("Ymd").$count+1;
                }

                if($model->sn==null){
                    $model->sn=time().rand(0,99999);
//                var_dump($model->sn);exit;
                }

                if ($model->save(false)) {

                    //保存文章
                    $content->load($request->post());
                    $content->goods_id=$model->id;
                    if ($content->save()) {
                        \Yii::$app->session->setFlash("success","编辑成功");
                    }

                    //删除之前的
                    GoodsImg::deleteAll(['goods_id'=>$id]);
                    //保存多图
                    foreach ($model->images as $image){
                        //创建对象
                        $imgFiles=new GoodsImg();
                        //赋值
                        $imgFiles->goods_id=$model->id;
                        $imgFiles->path=$image;
                        //保存
                        $imgFiles->save();
                    }

                    \Yii::$app->session->setFlash("success","编辑成功");
                    return $this->redirect(["index"]);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        //获取图片
        $imgs=GoodsImg::find()->where(['goods_id'=>$id])->asArray()->all();
        //二维转一维(合成制定字段的一个数组)
        $imgs=array_column($imgs,"path");
        //显示图片
        $model->images=$imgs;

        return $this->render("add",compact("model","brandArr","goodsArr","content","cate"));
    }

    //删
    public function actionDel($id){
        if (Goods::findOne($id)->delete()) {
            if (GoodsIntro::findOne($id)->delete()) {
                if (GoodsImg::deleteAll(['goods_id'=>$id])) {
                    \Yii::$app->session->setFlash("success","删除成功");
                    return $this->redirect(["index"]);
                }
            }
        }
    }

    //end
//    public function actionEnd($id){
//        $model=Goods::findOne($id);
////        var_dump( $model->status);exit;
//        $model->status=1;
//        $model->save();
//        \Yii::$app->session->setFlash("success","禁用成功");
//        return $this->redirect(['index']);
//    }

    //start
//    public function actionStart($id){
//        $model=Goods::findOne($id);
////       var_dump( $model->status);exit;
//        $model->status=0;
//        $model->save();
//        \Yii::$app->session->setFlash("success","激活成功");
//        return $this->redirect(['index']);
//    }

    //ueditor
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.shop.com",//图片访问路径前缀

                ]
            ]
        ];
    }
}
