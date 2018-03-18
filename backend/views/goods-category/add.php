<a href="<?=\yii\helpers\Url::to(['index'])?>" class="btn btn-info">返回首页</a>
<hr/>
<?php
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,"name");
echo $form->field($model,"parent_id")->hiddenInput(['value'=>'0']);

echo \liyuze\ztree\ZTree::widget([
    'setting' => '{
			data: {
				simpleData: {
					enable: true,
					pIdKey:"parent_id",
				}
			},
				callback: {
				onClick: onClick
			    }
		}',
    'nodes' => $allJson

]);

echo $form->field($model,"intro")->textarea();

echo \yii\bootstrap\Html::submitButton("提交",['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
?>

<script>
    function onClick(e,treeId, treeNode) {
        //获取分类框id
          $("#goodscategory-parent_id").val(treeNode.id)
//        console.dir(treeNode.id);
//        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
//        zTree.expandNode(treeNode);
    }
</script>
