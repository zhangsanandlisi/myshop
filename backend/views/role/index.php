<?php
/* @var $this yii\web\View */
?>
<h1>权限列表</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<table class="table">
    <tr>
    <td>名称</td>
    <td>介绍</td>
        <td>权限</td>
    <td>编辑</td>
    <td>删除</td>
    </tr>
    <?php
    foreach ($roles as $role):
    ?>
    <tr>
    <td><?=strpos($role->name,'/')!==false?'--':''?><?=$role->name?></td>
    <td><?=$role->description?></td>
        <td><?php
               //得到当前角色所有权限
            $auth=Yii::$app->authManager;
            $pers=$auth->getPermissionsByRole($role->name);
            $html="";
            foreach ($pers as $per){
                $html.=$per->description.",";
            }
            $html=trim($html,',');
            echo $html;
            ?></td>
    <td><a href="<?=\yii\helpers\Url::to(['edit','name'=>$role->name])?>" class="btn btn-info">编辑</a></td>
    <td><a href="<?=\yii\helpers\Url::to(['del','name'=>$role->name])?>" class="btn btn-danger">删除</a></td>
    </tr>
    <?php
    endforeach;
    ?>
</table>
