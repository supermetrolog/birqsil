<?php

namespace backend\models\DTO;

use common\models\AR\UserAccessToken;

class CredentialDto
{
    public string $token;
    public int $expire;

    public function __construct(private UserAccessToken $accessToken)
    {
        $this->token = $this->accessToken->token;
        $this->expire = $this->accessToken->expire;
    }
}