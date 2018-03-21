<?php

namespace backend\controllers;

use backend\models\Brand;
use Codeception\Lib\Generator\PageObject;
use crazyfd\qiniu\Qiniu;
use yii\data\Pagination;
use yii\helpers\Json;
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
            'pageSize' => 3,
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

//            //接收图片
//            $model->imgFile=UploadedFile::getInstance($model,"imgFile");
//            //定义一个空的
//            $path="";
//            if ($model->imgFile) {
//                //定义路径
//                $path="images/".time().".".$model->imgFile->extension;
//                //移动到新目录
//                $model->imgFile->saveAs($path,false);
//            }

            //后台验证
            if ($model->validate()) {
//                $model->logo=$path;
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

//            //接收图片
//            $model->imgFile=UploadedFile::getInstance($model,"imgFile");
//            //定义一个空的
//              $path="";
//            if ($model->imgFile) {
//                //定义路径
//                $path="images/".time().".".$model->imgFile->extension;
//                //移动到新目录
//                $model->imgFile->saveAs($path,false);
//            }

            //后台验证
            if ($model->validate()) {
//                if($path){
//                    $model->logo=$path;
//                }
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

    //删
    public function actionDel($id){
        if (Brand::findOne($id)->delete()) {
            \Yii::$app->session->setFlash("danger","删除成功");
            return $this->redirect(["index"]);
        }
    }

    //软删
    public function actionEnd($id)
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

    //激活
    public function actionStart($id)
    {
        //找到数据
        $model = Brand::findOne($id);
        //重新复值
        $model->status=1;
        //保存数据
        $model->save();
        \Yii::$app->session->setFlash("success", "激活成功");
        return $this->redirect(["index"]);


    }

    //webuploader
//    public function actionUpload(){
//
////        //得到图片
//         $file=UploadedFile::getInstanceByName("file");
////        var_dump($file);exit;
//         $path="images/".time().".".$file->extension;
//        if ($file->saveAs($path,"false")) {
//              $rst= [
//                  'code'=>'0',
//                  'url'=>"/".$path,//http://domain/图片地址
//                  'attachment'=>$path//图片地址
//              ];
//
//              //返回json数据
//            return Json::encode($rst);
//        }else{
//            $res= [
//                'code'=>'1',
//                'msg'=>'error'
//            ];
//            return Json::encode($res);
//        }
//    }

    //qiniu
    public function actionUpload(){


        switch (\Yii::$app->params["uploadType"]){

            case "127.0.0.1":


                //得到图片
         $file=UploadedFile::getInstanceByName("file");
//        var_dump($file);exit;
         $path="images/".time().".".$file->extension;
        if ($file->saveAs($path,"false")) {
              $rst= [
                  'code'=>'0',
                  'url'=>"/".$path,//http://domain/图片地址
                  'attachment'=>$path//图片地址
              ];

              //返回json数据
            return Json::encode($rst);
        }else{
            $res= [
                'code'=>'1',
                'msg'=>'error'
            ];
            return Json::encode($res);
        }
        break;

            case "qiniu":

            $ak = 'g0JM0Ty4ab2Zyxarhx704pH8gJnEMfDx6cgoptbI';//id
            $sk = 'WEgGOmuxS_CdUCtxB2GueESQkPPIALRmarzftcyF';//password
            $domain = 'http://p5uakr9xk.bkt.clouddn.com/';//yuming
            $bucket = 'zhangsan';//kongjianmingcheng
            $zone = 'south_china';

            $qiniu = new Qiniu($ak, $sk,$domain, $bucket,$zone);
            $key = time();
            //pinglujing
            $key=$key.strtolower(strrchr($_FILES['file']['name'], '.'));
            $qiniu->uploadFile($_FILES['file']['tmp_name'],$key);
            $url = $qiniu->getLink($key);

            $rst= [
                'code'=>'0',
                'url'=>$url,//http://domain/图片地址
                'attachment'=>$url//图片地址
            ];

            //返回json数据
            return Json::encode($rst);

            break;
        }


    }
}
