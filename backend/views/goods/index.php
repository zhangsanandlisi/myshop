<?php
/* @var $this yii\web\View */
?>
<h1>商品管理</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<table class="table">
    <tr>
        <td>编号</td>
        <td>名称</td>
        <td>分类</td>
        <td>品牌</td>
        <td>图片</td>
        <td>价格</td>
        <td>库存</td>
        <td>货号</td>
        <td>状态</td>
        <td>排序</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    <?php
    foreach ($models as $model):
        ?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->goods->name?></td>
            <td><?=$model->brand->name?></td>
            <td><?=$model->logo?></td>
            <td><?=$model->price?></td>
            <td><?=$model->stock?></td>
            <td><?=$model->sn?></td>
            <td><?php
                if($model->status){
                    echo \yii\helpers\Html::a("","end?id=$model->id",['class'=>'glyphicon glyphicon-ok','style'=>'color:green']);
                }else{
                    echo \yii\helpers\Html::a("","start?id=$model->id",['class'=>'glyphicon glyphicon-remove','style'=>'color:red']);
                }
                ?></td>
            <td><?=$model->sort?></td>
            <td><?=date("Ymd H:i:s",$model->create_time)?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['edit','id'=>$model->id])?>" class="btn btn-info">编辑</a>
                <a href="<?=\yii\helpers\Url::to(['del','id'=>$model->id])?>" class="btn btn-info">删除</a>
            </td>
        </tr>
        <?php
    endforeach;
    ?>
</table>

<?=\yii\widgets\LinkPager::widget([
    'pagination' => $page,
]);?>
