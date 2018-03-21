<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 商品名称
 * @property int $category_id 商品分类
 * @property int $brand_id 商品品牌
 * @property string $logo 商品图片
 * @property string $price 商品价格
 * @property int $stock 库存
 * @property int $sn 货号
 * @property int $status 状态
 * @property int $sort 排序
 * @property int $create_time 创建时间
 */
class Goods extends \yii\db\ActiveRecord
{
    //定义多图上传属性
    public $images;

    //静态属性
    public static $statusc=[0=>"禁用",1=>"激活"];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id', 'brand_id', 'logo', 'price', 'stock', 'status', 'sort','images'], 'required'],
            [['sn'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'category_id' => '商品分类',
            'brand_id' => '品牌',
            'logo' => '商标',
            'price' => '价格',
            'stock' => '库存',
            'sn' => '货号',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '创建时间',
            'images'=>'商品图片'
        ];
    }

    //时间注入
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('                                                NOW()'),
            ],
        ];
    }

    //1对1品牌
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }

    //1对1商品分类
    public function getGoods(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'category_id']);
    }
}
