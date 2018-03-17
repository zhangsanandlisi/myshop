<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $intro 简介
 * @property int $sort 排序
 * @property int $status 状态
 * @property int $cate_id 分类id
 * @property int $create_time 创建时间
 * @property int $update_time 编辑时间
 */
class Article extends \yii\db\ActiveRecord
{

    public static $statusc=[0=>'禁用',1=>'激活'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'intro', 'sort', 'status', 'cate_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
            'cate_id' => '文章分类',
        ];
    }

    //1对1
    public function getCate(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'cate_id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }
}
