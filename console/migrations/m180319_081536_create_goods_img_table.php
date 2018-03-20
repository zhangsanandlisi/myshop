<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_img`.
 */
class m180319_081536_create_goods_img_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_img', [
            'id' => $this->primaryKey(),
            'path'=>$this->string()->notNull()->comment("图片路径"),
            'goods_id'=>$this->integer()->notNull()->comment("所属商品")
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_img');
    }
}
