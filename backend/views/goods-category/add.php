<?php
/* @var $this yii\web\View */
?>
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

//定义js代码块
$js=<<<js
//展开所有节点
var treeObj = $.fn.zTree.getZTreeObj("w1");

//得到当前节点
var node = treeObj.getNodeByParam("id","$model->parent_id", null);
//选中节点
treeObj.selectNode(node);
//设置parent_id
$("#goodscategory-parent_id").val($model->parent_id);

//展开所有节点
treeObj.expandAll(true);
js;

//注册js
$this->registerJs($js);
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
