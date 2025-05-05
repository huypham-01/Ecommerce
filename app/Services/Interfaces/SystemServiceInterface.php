<?php

namespace App\Services\Interfaces;

/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface SystemServiceInterface
{
    public function save($request, $languageId);
}
