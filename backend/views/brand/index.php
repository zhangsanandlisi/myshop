<?php
/* @var $this yii\web\View */
?>
<h1>品牌列表</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<table class="table">
    <tr>
    <td>编号</td>
    <td>名称</td>
    <td>状态</td>
    <td>排序</td>
    <td>图片</td>
    <td>简介</td>
    <td>编辑</td>
    <td>删除</td>
    </tr>
    <?php
    foreach ($models as $model):
    ?>
    <tr>
    <td><?=$model->id?></td>
    <td><?=$model->name?></td>
    <td><?php
        if($model->status){
           echo \yii\bootstrap\Html::a("","end?id=$model->id",['class'=>'glyphicon glyphicon-ok','style'=>'color:green']);
        }else{
            echo \yii\bootstrap\Html::a("","start?id=$model->id",['class'=>'glyphicon glyphicon-remove','style'=>'color:red']);
        }
        ?></td>
    <td><?=$model->sort?></td>
    <td><?php
        $path=strpos($model->logo,"ttp://")?$model->logo:"/".$model->logo;
        echo \yii\helpers\Html::img($path,['height'=>'38']);
        ?></td>
    <td><?=$model->intro?></td>
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