<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\components\GoodsCart;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    //分类页
    public function actionList($id)
    {
        //通过id找到当前分类对
        $cate=GoodsCategory::findOne($id);
        //再通过id找到所有下级分类
        $nextCate=GoodsCategory::find()->where(['tree'=>$cate->tree])->andWhere(['>','left',$cate->left])->andWhere(['<','right',$cate->right])->asArray()->all();
        //二维换一维
        $cateId=array_column($nextCate,'id');
//        var_dump($cateId);exit;
        //当前商品所有分类
        $goods=Goods::find()->where(['in','category_id',$cateId])->all();
//        var_dump($goods);exit;

        return $this->render('list',compact('goods'));
    }

    //商品详情页
    public function actionDetail($id){
        $model=Goods::findOne($id);

        return $this->render('detail',compact('model'));
    }

    //购物车添加
    public function actionAddCart($id,$amount){

        //判断商品是否存在
        if(Goods::findOne($id)===null){
            return $this->redirect(['index/index']);
        }

        //判断是否登陆
        if(\Yii::$app->user->isGuest){
//            //得到cookie对象
//            $getCookie=\Yii::$app->request->cookies;
//            //得到原来购物车的对象
//            $cart=$getCookie->getValue('cart',[]);
//            //判断当前商品id是否存在
//            if (array_key_exists($id,$cart)) {
//                //存在就加
//                $cart[$id]+=$amount;
//            }else{
//                $cart[$id]=(int)$amount;
//            }
////            var_dump($cart);exit;
//
//            //未登录保存cookie
//            //创建cookie
//            $setcookie=\Yii::$app->response->cookies;
//            //创建对象
//            $cookie=new Cookie([
//                'name'=>'cart',
//                'value' => $cart,
//                'expire' => time()+3600*24
//            ]);
//            //添加cookie
//            $setcookie->add($cookie);
            (new GoodsCart())->add($id,$amount)->save();


        }else{
            //已登录
            //判断当前用户是否有商品
            $userId=\Yii::$app->user->id;
            $cart=Cart::findOne(['goods_id'=>$id,'user_id'=>$userId]);
            if($cart){
                //修改
                $cart->amount+=$amount;

            }else{
                //创建一个新的对象
                $cart=new Cart();
                //赋值
                $cart->goods_id=$id;
                $cart->user_id=$userId;
                $cart->amount=$amount;

            }
            //保存
            $cart->save();
        }
        return $this->redirect(['flow']);
//        var_dump($id,$amount);exit;
    }

    //购物显示
    public function actionFlow(){

        //判断是否登录
        if(\Yii::$app->user->isGuest){
            //未登从cookie取值
            $cart=\Yii::$app->request->cookies->getValue('cart',[]);
            //取出$cart中所有key值
            $goodsKey=array_keys($cart);
            //取出购物车的所有商品
            $goods=Goods::find()->where(['in','id',$goodsKey])->all();
//            var_dump($goods);exit;
        }else{
            //登录从数据库取值
            $cart=Cart::find()->where(['user_id'=>\Yii::$app->user->id])->all();
            $cart=ArrayHelper::map($cart,'goods_id','amount');
//            var_dump($cart);exit;
            //取出$cart中所有key值
            $goodsKey=array_keys($cart);
            //取出购物车的所有商品
            $goods=Goods::find()->where(['in','id',$goodsKey])->all();
        }

        return $this->render('flow',compact('goods','cart'));
    }

    //购物编辑
    public function actionUpdateCart($id,$amount){
        if(\Yii::$app->user->isGuest){
//            //从cookie中取值
//            $cart=\Yii::$app->request->cookies->getValue('cart',[]);
//            //修改对应数据
//            $cart[$id]=$amount;
//            //把$cart存到购物车
//            //创建cookie
//            $setcookie=\Yii::$app->response->cookies;
//            //创建对象
//            $cookie=new Cookie([
//                'name'=>'cart',
//                'value' => $cart
//            ]);
//            //添加cookie
//            $setcookie->add($cookie);
            (new GoodsCart())->edit($id,$amount)->save();
        }else{

        }
    }

    //删
    public function actionDelCart($id){
        if(\Yii::$app->user->isGuest){
//            //从cookie中取值
//            $cart=\Yii::$app->request->cookies->getValue('cart',[]);
//            //删除对应数据
//            unset($cart[$id]);
//            //把$cart存到购物车
//            //创建cookie
//            $setcookie=\Yii::$app->response->cookies;
//            //创建对象
//            $cookie=new Cookie([
//                'name'=>'cart',
//                'value' => $cart
//            ]);
//            //添加cookie
//            $setcookie->add($cookie);
            (new GoodsCart())->del($id)->save();
            return Json::encode([
                'status'=>1,
                'msg'=>"删除成功"
            ]);
        }
    }

    //测试
    public function actionTest(){
        $getCookie=\Yii::$app->request->cookies;
        var_dump($getCookie->getValue('cart'));
    }

}
