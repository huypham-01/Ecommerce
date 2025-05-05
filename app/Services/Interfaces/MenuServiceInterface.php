<?php

namespace App\Services\Interfaces;

/**
 * Interface MenuServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuServiceInterface
{
    public function paginate($request, $languageId);
    public function save($request, $languageId);
    public function saveChildren($request, $languageId, $menu);
    public function dragUpdate(array $json, int $menuCatalogueId, int $languageId);
    public function getAndConvertMenu($menu, $language);
    public function convertMenu($menuList);
    public function destroy($id);
}
