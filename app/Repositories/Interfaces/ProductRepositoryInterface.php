<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProductServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getProductById(int $id = 0, $language_id = 0);
    public function findProductForPromotion($condition = [], $relation = []);
}
