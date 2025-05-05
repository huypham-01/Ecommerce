<?php

namespace App\Repositories\Interfaces;

/**
 * Interface AttributeServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeRepositoryInterface extends BaseRepositoryInterface
{
    public function getAttributeById(int $id = 0, $language_id = 0);
    public function searchAttributes(string $keyword = '', array $option = [], int $languageId);
    public function findAttributeByIdArray(array $attributeArray = [], int $languageId = 0);
}
