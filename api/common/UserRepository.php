<?php

namespace app\api\common;


use app\api\common\models\User;
use app\api\UserRepositoryInterface;
use yii\base\Component;

class UserRepository extends Component implements UserRepositoryInterface
{
    public $users = [];

    public function findIdentityByAccessToken($accessToken, $type = null)
    {
        foreach($this->users as $user) {
            if ($user['accessToken'] === $accessToken) {
                return new User($user);
            }
        }

        return null;
    }

    public function findIdentity($id)
    {
        return isset($this->users[$id]) ? new User($this->users[$id]) : null;
    }

}