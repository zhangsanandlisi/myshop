<?php

/** @var $this \yii\web\View */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();
echo $form->field($model,'permissions')->inline()->checkboxList($per);

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
echo '&nbsp';
echo \yii\bootstrap\Html::a("返回首页","index",['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();