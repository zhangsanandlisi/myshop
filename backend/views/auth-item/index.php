<?php
/* @var $this yii\web\View */
?>
<h1>权限列表</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<table class="table">
    <tr>
    <td>名称</td>
    <td>介绍</td>
    <td>编辑</td>
    <td>删除</td>
    </tr>
    <?php
    foreach ($pers as $per):
    ?>
    <tr>
    <td><?=strpos($per->name,'/')!==false?'--':''?><?=$per->name?></td>
    <td><?=$per->description?></td>
    <td><a href="<?=\yii\helpers\Url::to(['edit','name'=>$per->name])?>" class="btn btn-info">编辑</a></td>
    <td><a href="<?=\yii\helpers\Url::to(['del','name'=>$per->name])?>" class="btn btn-danger">删除</a></td>
    </tr>
    <?php
    endforeach;
    ?>
</table>
