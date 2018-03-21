<?php

/** @var $this \yii\web\View */

$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,"name");
echo $form->field($model,"category_id")->dropDownList($cate);
echo $form->field($model,"brand_id")->dropDownList($brandArr);
echo $form->field($model,"logo")->widget(\manks\FileInput::className(),[]);

echo $form->field($model, 'images')->widget(\manks\FileInput::className(), [
    'clientOptions' => [
        'pick' => [
            'multiple' => true,
        ],
        // 'server' => Url::to('upload/u2'),
        // 'accept' => [
        // 	'extensions' => 'png',
        // ],
    ],
]);

echo $form->field($model,"price");
echo $form->field($model,"stock");
echo $form->field($model,"sn");
echo $form->field($model,"status")->inline()->radioList(\backend\models\Goods::$statusc,['value'=>1]);
echo $form->field($model,"sort");
echo $form->field($content,"content")->widget('kucha\ueditor\UEditor',[]);

echo \yii\bootstrap\Html::submitButton("提交",['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();