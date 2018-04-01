<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m180331_065255_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment("商品id"),
            'user_id'=>$this->integer()->comment("用户id"),
            'amount'=>$this->integer()->comment("数量"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cart');
    }
}
