<?php

namespace app\api\modules\v1\controllers;


use app\api\common\models\User;
use yii\rest\Controller;

class UserController extends Controller
{
    public function actionIndex()
    {
        return User::all();
    }
}