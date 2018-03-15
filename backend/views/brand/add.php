<?php
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,"name");
echo $form->field($model,"status")->inline()->radioList(\backend\models\Brand::$statusa,['value'=>1]);
echo $form->field($model,"sort");
echo $form->field($model,"imgFile")->fileInput();
echo $form->field($model,"intro")->textarea();

echo \yii\bootstrap\Html::submitButton("提交",['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();