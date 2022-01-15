<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\UserInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Traits\ResponseAPI;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    // Use ResponseAPI Trait in this repository
    use ResponseAPI;

    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
