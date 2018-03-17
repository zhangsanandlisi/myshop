<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m180316_113708_create_article_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->comment("分类名称"),
            'intro'=>$this->string()->notNull()->comment("简介"),
            'sort'=>$this->integer()->notNull()->comment("排序"),
            'status'=>$this->integer()->notNull()->comment("状态"),
            'is_help'=>$this->integer()->notNull()->comment("是不是帮助类")
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article_category');
    }
}
