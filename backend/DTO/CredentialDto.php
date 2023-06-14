<?php

namespace backend\DTO;

use common\models\AR\UserAccessToken;

class CredentialDto
{
    public string $token;
    public int $expire;

    public function __construct(private readonly UserAccessToken $accessToken)
    {
        $this->token = $this->accessToken->token;
        $this->expire = $this->accessToken->expire;
    }
}