<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/31
 * Time: 11:34
 */

namespace frontend\components;


use frontend\models\Cart;
use yii\base\Component;
use yii\web\Cookie;

class GoodsCart extends Component
{
     //创建一个私有的属性储存购物数据
    private $cart;

    //创建构造函数
    public function __construct(array $config = [])
    {
        //得到cookie对象
        $getCookie=\Yii::$app->request->cookies;
        //得到原来购物车的对象
        $this->cart=$getCookie->getValue('cart',[]);
        parent::__construct($config);
    }

    //增
    public function add($id,$num){
//        $id=$id-0;
        //判断当前商品id是否存在
//        var_dump($id,$num);exit;
        if (array_key_exists($id,$this->cart)) {
            $this->cart[$id] += $num;
        } else {
            $this->cart[$id] = (int)$num;
        }
        return $this;
    }

    //改
    public function edit($id,$num){
        //判定
        if( $this->cart[$id]){
            //修改对应数据
            $this->cart[$id]=$num;
        }
          return $this;
    }

    //删
    public function del($id){
        unset($this->cart[$id]);
        return $this;
    }

    //查
    public function get(){
        return $this->cart;
    }

    //数据同步
    public function dbSyn(){
        //把数据同步到数据库
        $userId=\Yii::$app->user->id;
        foreach($this->cart as $goodsKey=>$amount){
            $cartDb=Cart::findOne(['goods_id'=>$goodsKey,'user_id'=>$userId]);
            if($cartDb){
                //修改
                $cartDb->amount+=$amount;

            }else{
                //创建一个新的对象
                $cartDb=new Cart();
                //赋值
                $cartDb->goods_id=$goodsKey;
                $cartDb->user_id=$userId;
                $cartDb->amount=$amount;

            }
            //保存
            $cartDb->save();
        }
        return $this;
    }

    //清空本地cookie数据
    public function flush(){
        $this->cart=[];
        return $this;
    }

    //保存
    public function save(){
        //创建cookie
            $setcookie=\Yii::$app->response->cookies;
            //创建对象
            $cookie=new Cookie([
                'name'=>'cart',
                'value' => $this->cart,
                'expire' => time()+3600*24
            ]);
            //添加cookie
            $setcookie->add($cookie);
    }
}