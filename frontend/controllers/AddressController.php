<?php

namespace frontend\controllers;

use frontend\models\Address;
use yii\helpers\Json;
use yii\web\Request;

class AddressController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models=Address::find()->where(['user_id'=>\Yii::$app->user->id])->all();
        return $this->render('index',compact('models'));
    }

    //添加
    public function actionAdd(){
        $request=new Request();
        if ($request->isPost) {
            $model=new Address();
            $model->load($request->post());
            if($model->validate()){
//                var_dump($model->status);exit;
                $model->user_id=\Yii::$app->user->id;
                //赋值
                if($model->status===null){
                    $model->status=0;
                }else{
                    Address::updateAll(['status'=>0],['user_id'=>$model->user_id]);
                    $model->status=1;
                }
//                $model->status=$model->status===null?0:1;
                //保存
                if ($model->save()) {
                    $result=[
                        'status'=>1,
                        'msg'=>'操作成功',
                    ];
                    return Json::encode($result);
                }
            }else{

            }
        }
    }

    //删
    public function actionDel($id){
//        var_dump($id);exit;
        if (Address::findOne(['id'=>$id,'user_id'=>\Yii::$app->user->id])->delete()) {
            return $this->redirect(['index']);
        }
    }

    //改
    public function actionChage($id){
//        var_dump($id);exit;
        $model=Address::findOne(['id'=>$id,'user_id'=>\Yii::$app->user->id]);
        Address::updateAll(['status'=>0],['user_id'=>$model->user_id]);
        $model->status=1;
        $model->save();
            return $this->redirect(['index']);

    }
}
