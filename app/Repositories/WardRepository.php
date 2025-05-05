<?php

namespace App\Repositories;
use App\Models\User;
use App\Repositories\Interfaces\WardRepositoryInterface;

/**
 * Class UserService
 * @package App\Services
 */
class WardRepository implements WardRepositoryInterface
{

    public function getAllPaginate() {
        return User::paginate(15);
    }
}
