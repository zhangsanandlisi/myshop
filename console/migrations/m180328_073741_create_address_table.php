<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180328_073741_create_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer()->comment("用户id"),
            'name'=>$this->string()->comment("收件人"),
            'province'=>$this->string()->comment("省"),
            'city'=>$this->string()->comment("市"),
            'county'=>$this->string()->comment("区县"),
            'address'=>$this->string()->comment("详细地址"),
            'mobile'=>$this->string()->comment("电话"),
            'status'=>$this->integer()->comment("状态"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('address');
    }
}
