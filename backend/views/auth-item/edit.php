<?php

/** @var $this \yii\web\View */

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['disabled'=>'disabled']);
echo $form->field($model,'description')->textarea();

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();