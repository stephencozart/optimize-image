<?php

namespace app\api\common;


use yii\filters\auth\HttpBasicAuth;

class Module extends \yii\base\Module
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
        ];

        return $behaviors;
    }
}