<?php

namespace App\Repositories;
use App\Models\Router;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class RouterService
 * @package App\Services
 */
class RouterRepository extends BaseRepository implements RouterRepositoryInterface
{
    protected $model;
    public function __construct(
        Router $model,
    ) {
        $this->model = $model;
    }

}
