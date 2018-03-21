<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m180321_012849_create_admin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment("用户名"),
            'email'=>$this->string()->comment("邮箱"),
            'create_time'=>$this->string()->comment("创建时间"),
            'last_time'=>$this->string()->comment("最后登录时间"),
            'last_ip'=>$this->string()->comment("最后登录IP")
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('admin');
    }
}
