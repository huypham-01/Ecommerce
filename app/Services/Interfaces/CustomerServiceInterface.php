<?php

namespace App\Services\Interfaces;

/**
 * Interface CustomerServiceInterface
 * @package App\Services\Interfaces
 */
interface CustomerServiceInterface
{
    public function paginate($request);
    public function create($request);
    public function update($id, $request);
    public function destroy($id);
}
