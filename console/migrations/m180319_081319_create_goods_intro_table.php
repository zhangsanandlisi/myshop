<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m180319_081319_create_goods_intro_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_intro', [
            'id' => $this->primaryKey(),
            'content'=>$this->text()->notNull()->comment("内容"),
            'goods_id'=>$this->text()->notNull()->comment("所属商品")
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_intro');
    }
}
