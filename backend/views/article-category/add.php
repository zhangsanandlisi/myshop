<?php
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,"name");
echo $form->field($model,"status")->inline()->radioList(\backend\models\ArticleCategory::$statusc,['value'=>'1']);
echo $form->field($model,"sort");
echo $form->field($model, 'is_help')->inline()->radioList(\backend\models\ArticleCategory::$is_helps,['value'=>'0']);
echo $form->field($model,"intro")->textarea();

echo \yii\bootstrap\Html::submitButton("提交",['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();