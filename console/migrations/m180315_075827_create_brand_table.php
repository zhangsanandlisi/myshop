<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m180315_075827_create_brand_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->comment("名称"),
            'status'=>$this->integer()->notNull()->comment("状态"),
            'sort'=>$this->integer()->notNull()->comment("排序"),
            'logo'=>$this->string()->comment("图片"),
            'intro'=>$this->string()->notNull()->comment("简介")
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('brand');
    }
}
