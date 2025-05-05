<?php

namespace App\Services\Interfaces;

/**
 * Interface ProductServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductCatalogueServiceInterface
{
    public function paginate($request);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function destroy($id);
}
