<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property string $name 收件人
 * @property string $province 省
 * @property string $city 市
 * @property string $county 区县
 * @property string $address 详细地址
 * @property string $mobile 电话
 * @property int $status 状态
 */
class Address extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province', 'city', 'county', 'address', 'mobile'], 'required'],
            [['status'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'name' => '收件人',
            'province' => '省',
            'city' => '市',
            'county' => '区县',
            'address' => '详细地址',
            'mobile' => '电话',
            'status' => '状态',
        ];
    }
}
