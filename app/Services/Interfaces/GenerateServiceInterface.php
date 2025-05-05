<?php

namespace App\Services\Interfaces;

/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface GenerateServiceInterface
{
    public function paginate($request, $languageId);
    public function create($request);
    public function update($id, $request);
    public function destroy($id);
}
