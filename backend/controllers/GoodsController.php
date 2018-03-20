<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
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
    /*
     * 查
     */
    public function actionIndex()
    {
        $query=Goods::find();

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

                if($model->sn==null){
                    $model->sn=time().rand(0,99999);
//                var_dump($model->sn);exit;
                }

                if ($model->save(false)) {

                    //保存文章
                    $content->load($request->post());
                    $content->goods_id=$model->id;
                    if ($content->save()) {
                        \Yii::$app->session->setFlash("success","保存成功");
                    }

                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(["index"]);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render("add",compact("model","brandArr","goodsArr","content"));
    }

    /*
        * 编辑
        */
    public function actionEdit($id){
        $model=Goods::findOne($id);

        //创建文章内容对像
        $content=GoodsIntro::findOne($id);

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

                    \Yii::$app->session->setFlash("success","编辑成功");
                    return $this->redirect(["index"]);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }

        return $this->render("add",compact("model","brandArr","goodsArr","content"));
    }

    //删
    public function actionDel($id){
        if (Goods::findOne($id)->delete()) {
            if (GoodsIntro::findOne($id)->delete()) {
                \Yii::$app->session->setFlash("success","删除成功");
                return $this->redirect(["index"]);
            }
        }
    }

    //end
    public function actionEnd($id){
        $model=Goods::findOne($id);
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash("success","禁用成功");
        return $this->redirect(['index']);
    }

    //start
    public function actionStart($id){
        $model=Goods::findOne($id);
        $model->status=1;
        $model->save();
        \Yii::$app->session->setFlash("success","激活成功");
        return $this->redirect(['index']);
    }


    //图片上传
//    public function actionUpload()
//    {
//        $ak = 'g0JM0Ty4ab2Zyxarhx704pH8gJnEMfDx6cgoptbI';
//        $sk = 'WEgGOmuxS_CdUCtxB2GueESQkPPIALRmarzftcyF';
//        $domain = 'http://p5uakr9xk.bkt.clouddn.com/';
//        $bucket = 'zhangsan';
//        $zone = 'south_china';
//
//        $qiniu = new Qiniu($ak, $sk, $domain, $bucket, $zone);
//        $key = time();
//        $key .= strtolower(strrchr($_FILES['file']['name'], '.'));
//
//        $qiniu->uploadFile($_FILES['file']['tmp_name'], $key);
//        $url = $qiniu->getLink($key);
////        var_dump($url);exit;
//
//        $res = [
//            'code' => '0',
//            'url' => $url,
//            'attachment' => $url
//        ];
//        return Json::encode($res);
//    }

}
