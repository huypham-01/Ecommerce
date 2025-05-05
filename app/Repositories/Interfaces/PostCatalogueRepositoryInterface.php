<?php

namespace App\Repositories\Interfaces;

/**
 * Interface PostCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueRepositoryInterface extends BaseRepositoryInterface
{
    public function getPostCatalogueById(int $id = 0, $language_id = 0);
}
