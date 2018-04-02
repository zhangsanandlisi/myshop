<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\Order;
use backend\models\OrderDetail;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Express;
use frontend\models\Pay;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Request;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['/user/login','url'=>'/order/index']);
        }

        //用户id
        $userId=\Yii::$app->user->id;

        //收货人
        $addresss=Address::find()->where(['user_id'=>$userId])->all();

        //快递
        $expresss=Express::find()->all();

        //支付
        $pays=Pay::find()->all();

        //商品
        $cart=Cart::find()->where(['user_id'=>$userId])->asArray()->all();
        //二维转一维
        $cart=ArrayHelper::map($cart,'goods_id','amount');
        //取键
        $goodsId=array_keys($cart);
        //商品
        $goods=Goods::find()->where(['in','id',$goodsId])->all();
//        var_dump($goods);exit;

        //商品总量
        $numTotal=0;
        //商品总价
        $priceTotal=0;
        foreach ($goods as $good){
            $priceTotal+=$good->price*$cart[$good->id];
            $numTotal+=$cart[$good->id];
        }

        //判断
        $request=new Request();
        if($request->isPost){

            //事务
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();//开启事务

            try {

                //创建对象
                $order=new Order();
                //取出地址
                $addressId=$request->post('address_id');
                $address=Address::findOne(['id'=>$addressId,'user_id'=>$userId]);
                //取出配送
                $expressId=$request->post('express');
                $express=Express::findOne(['id'=>$expressId]);
                //取出支付
                $payId=$request->post('pay');
                $pay=Pay::findOne($payId);

                //赋值
                $order->user_id=$userId;
                $order->name=$address->name;
                $order->province=$address->province;
                $order->city=$address->city;
                $order->county=$address->county;
                $order->detail_address=$address->address;
                $order->tel=$address->mobile;

                $order->delivery_id=$expressId;
                $order->delivery_name=$express->name;
                $order->delivery_price=$express->price;

                $order->payment_id=$payId;
                $order->payment_name=$pay->name;

                //订单总价
                $order->price=$priceTotal+$express->price;

                //订单状态
                $order->status=1;

                //订单号
                $order->trade_no=date("ymdHis").rand(1000,9999);

                //生成时间
                $order->create_time=time();

                //保存数据
                if ($order->save()) {

                    //循环入详情表
                    foreach ($goods as $good){

                        //找到当前商品
                        $curGoods=Goods::findone($good->id);
//                    var_dump($curGoods);exit;
                        //判断库存
                        if ($cart[$good->id]>$curGoods->stock) {
//                            exit("库存不足");
                            //抛出异常
                            throw new Exception("库存不足");
                        }

                        $orderDetail=new OrderDetail();
                        $orderDetail->order_id=$order->id;
                        $orderDetail->goods_id=$good->id;
                        $orderDetail->amount=$cart[$good->id];
                        $orderDetail->goods_name=$good->name;
                        $orderDetail->logo=$good->logo;
                        $orderDetail->price=$good->price;
                        $orderDetail->total_price=$good->price*$orderDetail->amount;
//var_dump($orderDetail->amount);exit;

                        if ($orderDetail->save()) {
                            //把当前商品库存减掉
                            $curGoods->stock= $curGoods->stock-$cart[$good->id];
//                        echo $curGoods->stock;exit;
                            $curGoods->save(false);
                        }else{
                            var_dump($orderDetail->errors);exit;
                        }
                    }
                }

                //清空购物车
                Cart::deleteAll(['user_id'=>$userId]);


                $transaction->commit();//提交事务

                return Json::encode([
                    'status'=>1,
                    'msg'=>"订单提交成功"
                ]);
            } catch(Exception $e) {
                $transaction->rollBack();//事务回滚
                return Json::encode([
                    'status'=>0,
                    'msg'=>$e->getMessage()
                ]);
            }



        }

        return $this->render('index',compact('addresss','expresss','pays','goods','cart','priceTotal','numTotal'));
    }

}
