<?php
/* @var $this yii\web\View */
?>
    <h1>商品分类</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<?= \leandrogehlen\treegrid\TreeGrid::widget([
    'dataProvider' => $dataProvider,
    'keyColumnName' => 'id',
    'parentColumnName' => 'parent_id',
    'parentRootValue' => '0', //first parentId value
    'pluginOptions' => [
        'initialState' => 'collapsed',
    ],
    'columns' => [
        'name',
        'id',
        'parent_id',
        ['class' => 'yii\grid\ActionColumn']
    ]
]); ?>
