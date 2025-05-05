<?php

namespace App\Services\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface SlideServiceInterface
{
    public function paginate($request);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function destroy($id);
    public function convertSlideArray(array $slide = []);
}
