<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180316_122259_create_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'title'=>$this->string()->notNull()->comment("标题"),
            'intro'=>$this->string()->notNull()->comment("简介"),
            'sort'=>$this->integer()->notNull()->comment("排序"),
            'status'=>$this->integer()->notNull()->comment("状态"),
            'cate_id'=>$this->integer()->notNull()->comment("分类id"),
            'create_time'=>$this->integer()->notNull()->comment("创建时间"),
            'update_time'=>$this->integer()->notNull()->comment("编辑时间")
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article');
    }
}
