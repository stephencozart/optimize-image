<?php

namespace app\api;


use yii\web\IdentityInterface;

interface UserRepositoryInterface
{
    /**
     * @param $accessToken
     * @param null $type
     * @return IdentityInterface
     */
    public function findIdentityByAccessToken($accessToken, $type = null);

    /**
     * @param $id
     * @return IdentityInterface
     */
    public function findIdentity($id);
}