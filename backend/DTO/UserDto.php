<?php

namespace backend\DTO;

use common\models\AR\User;

class UserDto
{
    public int $id;
    public string $email;
    public int $status;
    public string $created_at;
    public string|null $updated_at;

    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->id = $model->id;
        $this->email = $model->email;
        $this->status = $model->status;
        $this->created_at = $model->created_at;
        $this->updated_at = $model->updated_at;
    }
}