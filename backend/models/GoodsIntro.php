<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_intro".
 *
 * @property int $id
 * @property string $content 内容
 * @property int $goods_id 所属商品
 */
class GoodsIntro extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_intro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content','goods_id'], 'required'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'goods_id' => '所属商品',
        ];
    }
}
