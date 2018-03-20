<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m180319_074431_create_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->comment("商品名称"),
            'category_id'=>$this->integer()->notNull()->comment("商品分类"),
            'brand_id'=>$this->integer()->notNull()->comment("商品品牌"),
            'logo'=>$this->string()->notNull()->comment("商品图片"),
            'price'=>$this->decimal()->notNull()->comment("商品价格"),
            'stock'=>$this->integer()->notNull()->comment("库存"),
            'sn'=>$this->integer()->notNull()->comment("货号"),
            'status'=>$this->integer()->notNull()->comment("状态"),
            'sort'=>$this->integer()->notNull()->comment("排序"),
            'create_time'=>$this->integer()->notNull()->comment("创建时间"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods');
    }
}
