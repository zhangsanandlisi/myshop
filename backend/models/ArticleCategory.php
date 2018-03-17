<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property int $id
 * @property string $name 分类名称
 * @property string $intro 简介
 * @property int $sort 排序
 * @property int $status 状态
 * @property int $is_help 是不是帮助类
 */
class ArticleCategory extends \yii\db\ActiveRecord
{

    public static $statusc=[0=>"禁用",1=>"激活"];
    public static $is_helps=[0=>"否",1=>"是"];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'sort', 'status', 'is_help'], 'required'],
            [['name'],'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
            'is_help' => '是不是帮助类',
        ];
    }
}
