<?php
/* @var $this yii\web\View */
?>

<h1>文章列表</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<table class="table">
    <tr>
        <td>编号</td>
        <td>标题</td>
        <td>简介</td>
        <td>排序</td>
        <td>状态</td>
        <td>文章分类</td>
        <td>创建时间</td>
        <td>编辑时间</td>
        <td>编辑</td>
        <td>删除</td>
    </tr>
    <?php
    foreach ($models as $model):
        ?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->title?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>
            <td><?php
                if($model->status){
                    echo \yii\helpers\Html::a("","end?id=$model->id",['class'=>'glyphicon glyphicon-ok','style'=>'color:green']);
                }else{
                    echo \yii\helpers\Html::a("","start?id=$model->id",['class'=>'glyphicon glyphicon-remove','style'=>'color:red']);
                }
                ?></td>

            <td><?=$model->cate->name?></td>
            <td><?=date("Ymd H:i:s",$model->create_time)?></td>
            <td><?=date("Ymd H:i:s",$model->update_time)?></td>
            <td><a href="<?=\yii\helpers\Url::to(['edit','id'=>$model->id])?>" class="btn btn-info">编辑</a></td>
            <td><a href="<?=\yii\helpers\Url::to(['del','id'=>$model->id])?>" class="btn btn-danger">删除</a></td>
        </tr>
        <?php
    endforeach;
    ?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination' => $page
]);
?>
