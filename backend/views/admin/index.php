<?php
/* @var $this yii\web\View */
?>
<h1>管理员列表</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<table class="table">
    <tr>
        <td>编号</td>
        <td>用户名</td>
        <td>密码</td>
        <td>邮箱</td>
        <td>创建时间</td>
        <td>最后登录时间</td>
        <td>最后登录IP</td>
        <td>操作</td>
    </tr>
    <?php
    foreach ($models as $model):
        ?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->username?></td>
            <td><?=$model->password?></td>
            <td><?=$model->email?></td>
            <td><?=date("Ymd H:i:s",$model->create_time)?></td>
            <td><?=date("Ymd H:i:s",$model->last_time)?></td>
            <td><?=$model->last_ip?></td>
            <td><a href="<?=\yii\helpers\Url::to(['edit','id'=>$model->id])?>" class="btn btn-info">编辑</a></td>
            <td><a href="<?=\yii\helpers\Url::to(['del','id'=>$model->id])?>" class="btn btn-danger">删除</a></td>
        </tr>
        <?php
    endforeach;
    ?>
</table>
<?=\yii\widgets\LinkPager::widget([
        'pagination' => $page
]);
?>