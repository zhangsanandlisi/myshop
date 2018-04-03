<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\Order;
use backend\models\OrderDetail;
use EasyWeChat\Foundation\Application;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Express;
use frontend\models\Pay;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Request;
use Endroid\QrCode\QrCode;

class OrderController extends \yii\web\Controller
{
    //400
    public $enableCsrfValidation=false;

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
                    'msg'=>"订单提交成功",
                    'id'=>$order->id
                ]);
            } catch(Exception $e) {
                $transaction->rollBack();//事务回滚
                return Json::encode([
                    'status'=>0,
                    'msg'=>$e->getMessage()
                ]);
            }



        }

        return $this->render('index',compact('addresss','expresss','pays','goods','cart','priceTotal','numTotal','order'));
    }

    //支付
    public function actionPay($id){
        $order=Order::findOne($id);

        //判断支付方式


        return $this->render('pay',compact('order'));
    }

    public function actionTest($id){
        $order=Order::findOne($id);

        //引入配置
        $options = \Yii::$app->params['wx'];
//          var_dump($options);exit;

        //创建操作微信对象
        $app = new Application($options);

        //通过app得到支付对象
        $payment = $app->payment;

        //订单详情
        $attributes = [
            'trade_type'       => 'NATIVE', // 支付方式JSAPI（公众号支付），NATIVE（扫码支付），APP（app支付）...
            'body'             => '京西商城订单',
            'detail'           => '商品详情',
            'out_trade_no'     => $order->trade_no,
            'total_fee'        => 1, // 单位：分
            'notify_url'       => Url::to(['order/notify'],true), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            //'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];

        //通过订单详情生成订单
        $order = new \EasyWeChat\Payment\Order($attributes);

        //统一下单
        $result = $payment->prepare($order);
//        var_dump($result);exit;
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
//            $prepayId = $result->prepay_id;
//            echo $result->code_url;

            $qrCode = new QrCode($result->code_url);

            header('Content-Type: '.$qrCode->getContentType());
            echo $qrCode->writeString();
        }
    }

    //微信异步通信地址
    public function actionNotify(){
        //引入配置
        $options = \Yii::$app->params['wx'];
//          var_dump($options);exit;

        //创建操作微信对象
        $app = new Application($options);

        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
//            $order = 查询订单($notify->out_trade_no);
              $order=Order::findOne(['trade_no'=>$notify->out_trade_no]);
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->status!=1) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
               // $order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 2;//1等待 2完成
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;
    }
}
