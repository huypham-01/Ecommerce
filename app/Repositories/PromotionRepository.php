<?php

namespace App\Repositories;
use App\Models\Promotion;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface;

/**
 * Class PromotionService
 * @package App\Services
 */
class PromotionRepository extends BaseRepository implements PromotionRepositoryInterface
{
    protected $model;
    public function __construct(
        Promotion $model,
    ) {
        $this->model = $model;
    }
    
}
