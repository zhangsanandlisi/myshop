<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property string $name 名称
 * @property int $status 状态
 * @property int $sort 排序
 * @property string $logo 图片
 * @property string $intro 简介
 */
class Brand extends \yii\db\ActiveRecord
{
//    public $imgFile;
    //声明静态属性
    public static $statusa=[0=>'禁用',1=>'激活'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status', 'sort','logo'], 'required'],
            ['intro','safe'],
//            [['imgFile'],'image','extensions' => ['jpg','png','gif'],'skipOnEmpty' => true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'status' => '状态',
            'sort' => '排序',
            'logo' => '图片',
            'intro' => '简介',
        ];
    }
}
