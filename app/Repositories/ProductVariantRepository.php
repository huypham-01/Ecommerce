<?php

namespace App\Repositories;
use App\Models\ProductVariant;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{
    protected $model;
    public function __construct(
        ProductVariant $model,
    ) {
        $this->model = $model;
    }
    
}
