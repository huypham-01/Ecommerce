<?php

namespace App\Repositories;
use App\Models\CustomerCatalogue;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface;

/**
 * Class CustomerCatalogueService
 * @package App\Services
 */
class CustomerCatalogueRepository extends BaseRepository implements CustomerCatalogueRepositoryInterface
{
    protected $model;
    public function __construct(
        CustomerCatalogue $model,
    ) {
        $this->model = $model;
    }
}
