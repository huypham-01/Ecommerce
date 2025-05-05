<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProductServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductCatalogueRepositoryInterface extends BaseRepositoryInterface
{
    public function getProductCatalogueById(int $id = 0, $language_id = 0);
}
