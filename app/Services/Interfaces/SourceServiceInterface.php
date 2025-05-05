<?php

namespace App\Services\Interfaces;

/**
 * Interface SourceServiceInterface
 * @package App\Services\Interfaces
 */
interface SourceServiceInterface
{
    public function paginate($request);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function destroy($id);
}
