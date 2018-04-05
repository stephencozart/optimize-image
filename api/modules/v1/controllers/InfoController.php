<?php

namespace app\api\modules\v1\controllers;


use yii\rest\Controller;

class InfoController extends Controller
{
    public function actionIndex()
    {
        return [
            'name' => \Yii::$app->id,
            'status' => 'ok',
            'version' => 'v1'
        ];
    }
}