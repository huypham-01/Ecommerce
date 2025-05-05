<?php

namespace App\Repositories\Interfaces;

/**
 * Interface PostServiceInterface
 * @package App\Services\Interfaces
 */
interface PostRepositoryInterface extends BaseRepositoryInterface
{
    public function getPostById(int $id = 0, $language_id = 0);
}
