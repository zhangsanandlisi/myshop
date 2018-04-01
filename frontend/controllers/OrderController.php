<?php

namespace frontend\controllers;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['/user/login','url'=>'/order/index']);
        }
        return $this->render('index');
    }

}
