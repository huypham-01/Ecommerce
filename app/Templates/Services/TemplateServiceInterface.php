<?php

namespace App\Services\Interfaces;

/**
 * Interface {class}ServiceInterface
 * @package App\Services\Interfaces
 */
interface {class}{extend}ServiceInterface
{
    public function paginate($request);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function destroy($id);
}
